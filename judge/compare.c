/*
   compare -- compare program and testdata output and return diff output.

   This program is a compare script for 'testcase_run.sh' and executes
   a normal 'diff' to detect correct, presentation-error (whitespace
   mostly ignored) or wrong-answer results.

   Usage: compare <testdata.in> <program.out> <testdata.out> <result.out> <diff.out>

   <testdata.in>   File containing testdata input.
   <program.out>   File containing the program output.
   <testdata.out>  File containing the correct output.
   <result.out>    File describing the judging verdict.
   <diff.out>      File to write program/correct output differences to (optional).

   Exits successfully except when an internal error occurs. Program
   output is considered correct when diff.out is empty (if specified)
   and exitcode is zero.

   Output format of differences:

   - First a line stating from which line differences were found.
   - Then all lines from PREVLINES before that line until end of both
     <program.out> and <testdata.out>, formatted as:

	   '<PROGRAM LINE>' X '<TESTDATA LINE>'

	 The left and right sides are aligned and ending quote (') is
     replaced by an underscore (_) if the line is truncated. The
     middle 'X' is one of the following characters:
	 = both lines are identical
	 ! the lines are different
	 < left contains additional lines not present right
	 > vice versa
     $ only end-of-lines characters differ (e.g. LF vs. CR+LF); note
       that only LF is considered to begin a newline and all CR
       characters are stripped.
 */

#include "config.h"

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>

#include "lib.misc.h"
#include "lib.error.h"

#define min(a,b) ((a) < (b) ? (a) : (b))
#define max(a,b) ((a) > (b) ? (a) : (b))

/* Maximum characters to print per side on a line */
#define MAXPRINTLEN 60

/* Number of context lines printed before first line with differences */
#define PREVLINES 3

const char *progname;

/* filenames of commandline arguments */
char *testin, *testout, *progout, *result, *diffout;

/* Write result file with result message */
void writeresult(char *msg)
{
	FILE *resultfile;

	if ( !(resultfile = fopen(result,"w")) ) error(errno,"cannot open '%s'",result);

	fprintf(resultfile,"result=%s", msg);

	fclose(resultfile);
}

/* Definitions below */
int execdiff(int);
void writediff();

int main(int argc, char **argv)
{
	FILE *diffoutfile;

	/* Read arguments. Note that argc counts the number of arguments
	   including the name of the executed program (argv[0]), thus
	   (argc-1) is the real number of arguments. */
	progname = argv[0];
	if ( argc-1<4 ) error(0,"not enough arguments: %d given, 4 required",argc-1);
	if ( argc-1>5 ) error(0,"too many arguments: %d given, max. 5 accepted",argc-1);
	testin  = argv[1];
	progout = argv[2];
	testout = argv[3];
	result  = argv[4];
	/* Check for optional diff.out filename. */
	if ( (argc-1)==5 ) {
		diffout = argv[5];
	} else {
		diffout = NULL;
	}

	/* Exit when no diff output found (nothing to do anymore) */
	if ( execdiff(0)==0 ) {
		writeresult("Accepted");
		/* write empty diff.out if requested */
		if ( diffout != NULL ) {
			if ( (diffoutfile=fopen(diffout,"w")) == NULL ) {
				error(errno,"opening file '%s'",diffout);
			}
			fclose(diffoutfile);
		}
		return 0;
	}

	/* Check presentation error */
	if ( execdiff(1)==0 ) {
		writeresult("Presentation error");
	} else {
		/* We are left with the case of a wrong answer */
		writeresult("Wrong answer");
	}

	/* Exit when no 'diffout' file specified (nothing to do anymore) */
	if ( diffout==NULL || strlen(diffout)==0 ) return 0;

	writediff();

	return 0;
}

/* Calls 'diff' and returns whether progout and testout differ.
   Set ignore_ws for ignoring whitespace differences. 
   */
int execdiff(int ignore_ws)
{
	int redir_fd[3];
	char *cmdargs[4];
	pid_t cpid;
	FILE *rpipe;
	int status, differror;
	char line[256];

	/* Execute 'diff <diffoptions> progout testout'. */
	if ( ignore_ws ) {
		cmdargs[0] = "-abBE";
	} else {
		cmdargs[0] = "-aBZ";
	}
	cmdargs[1] = "-U0";
	cmdargs[2] = progout;
	cmdargs[3] = testout;
	redir_fd[0] = FDREDIR_NONE;
	redir_fd[1] = FDREDIR_PIPE;
	redir_fd[2] = FDREDIR_NONE;
	if ( (cpid = execute("diff",(const char **)cmdargs,4,redir_fd,1))<0 ) {
		error(errno,"running diff");
	}

	/* Bind diff stdout/stderr to pipe */
	if ( (rpipe = fdopen(redir_fd[1],"r"))==NULL ) {
		error(errno,"opening pipe from diff output");
	}

	/* Read stdout/stderr and check for output */
	differror = 0;
	while ( fgets(line,255,rpipe)!=NULL ) {
		if ( strlen(line)>0 ) differror = 1;
	}

	if ( fclose(rpipe)!=0 ) error(errno,"closing pipe from diff output");

	if ( waitpid(cpid,&status,0)<0 ) error(errno,"waiting for diff");

	/* Check diff exitcode */
	if ( WIFEXITED(status) && WEXITSTATUS(status)!=0 ) {
		if ( WEXITSTATUS(status)==1 ) { /* differences were found */
			differror = 1;
		} else { /* any other exitcode!=0 means diff internal error */
			error(0,"diff exited with exitcode %d",WEXITSTATUS(status));
		}
	}

	return differror;
}

/* Writes a readable diff output to 'diffout' */
void writediff()
{
	FILE *diffoutfile;
	FILE *inputfile[2];
	size_t maxlinelen[2], nlines[2];
	char *line[2];
	size_t linesize[2];
	int linenowidth;
	int endoffile[2];
	int i;
	size_t l;
	ssize_t dummy;
	int endlinediff, normaldiff;
	char diffchar, quotechar[2];
	char formatstr[256];
	int firstdiff = -1;

	if ( (diffoutfile =fopen(diffout,"w"))==NULL ) error(errno,"opening file '%s'",diffout);
	if ( (inputfile[0]=fopen(progout,"r"))==NULL ) error(errno,"opening file '%s'",progout);
	if ( (inputfile[1]=fopen(testout,"r"))==NULL ) error(errno,"opening file '%s'",testout);

	/* Find maximum line length and no. lines per input file.
	 * First initialize variables.
	 */
	for(i=0; i<2; i++) {
		line[i] = NULL;
		endoffile[i] = maxlinelen[i] = nlines[i] = linesize[i] = 0;
	}

	/* Read lines until end of file to find first difference and maxlinelen */
	for(l=0; !(endoffile[0] && endoffile[1]); l++) {

		/* Read line of each input file if not already end of file */
		for(i=0; i<2; i++) {
			if ( endoffile[i] ) {
				line[i][0] = 0;
				continue;
			}
			if ( getline(&line[i],&linesize[i],inputfile[i])>=0 ) {
				nlines[i]++;
			} else {
				/* We assume no errors other than EOF occur */
				endoffile[i] = 1;
				line[i][0] = 0;
			}
		}

		/* Check for differences: _one_ file ended or lines differ */
		if ( firstdiff==-1 &&
			 ( endoffile[0]^endoffile[1] ||
			   strcmp(line[0],line[1])!=0 ) ) firstdiff = l;

		/* Update maxlinelen with length of this line */
		for(i=0; i<2; i++) {
			stripendline(line[i]);
			if ( strlen(line[i])>maxlinelen[i] ) maxlinelen[i] = strlen(line[i]);
		}
	}

	/* Calculate max. line number width */
	for(linenowidth=1, l=10; max(nlines[0],nlines[1])>=l; linenowidth++) l *= 10;

	/* If no differences found, then some error occurred */
	if ( firstdiff==-1 ) error(0,"differences reported by 'diff', but none found");

	/* Reset file position to start */
	for(i=0; i<2; i++) rewind(inputfile[i]);

	/* Determine left/right printing length and construct format
	   string for printf later */
	for(i=0; i<2; i++) maxlinelen[i] = min(maxlinelen[i],MAXPRINTLEN);
	sprintf(formatstr,"%%%dd %%c%%-%ds %%c %%c%%-%ds\n",
	        linenowidth, (int)maxlinelen[0]+1, (int)maxlinelen[1]+1);

	/* Print first differences found header at beginning of file */
	fprintf(diffoutfile,"### DIFFERENCES FROM LINE %d ###\n",firstdiff+1);
	for(i=0; i<linenowidth; i++) fprintf(diffoutfile,"_");
	fprintf(diffoutfile,"%*s ? REFERENCE\n",(int)maxlinelen[0]+3,"TEAM");

	/* Loop over all common lines for printing */
	for(l=0; l<min(nlines[0],nlines[1]); l++) {

		/* We should check getline() returning -1 on
		 * EOF or errors, but we did so above.
		 */
		for(i=0; i<2; i++) dummy = getline(&line[i],&linesize[i],inputfile[i]);

		/* Check for endline (or normal) character differences */
		endlinediff = ( strcmp(line[0],line[1])!=0 );

		/* Strip endline characters */
		for(i=0; i<2; i++) stripendline(line[i]);

		/* Check for just normal character differences */
		normaldiff = ( strcmp(line[0],line[1])!=0 );

		/* Discern cases '!', '$' and '=' */
		if ( normaldiff ) {
			diffchar = '!';
		} else if ( endlinediff ) {
			diffchar = '$';
		} else {
			diffchar = '=';
		}

		if ( diffchar!='=' && l<firstdiff ) {
			error(0,"internal error: first difference on line %d != %d",(int)l,firstdiff);
		}

		/* Skip printing until PREVLINES before first difference line */
		if ( l+PREVLINES<firstdiff ) continue;

		/* Truncate lines for printing */
		for(i=0; i<2; i++) {
			if ( strlen(line[i])>maxlinelen[i] ) {
				line[i][maxlinelen[i]+1] = 0;
				line[i][maxlinelen[i]] = '_';
			} else {
				line[i][strlen(line[i])+1] = 0;
				line[i][strlen(line[i])] = '\'';
			}
		}

		fprintf(diffoutfile,formatstr,l+1,'\'',line[0],diffchar,'\'',line[1]);
	}

	/* Print lines for single continuing file */
	if ( l<max(nlines[0],nlines[1]) ) {
		if ( nlines[0]>l ) {
			i = 0;
			diffchar = '<';
			quotechar[0] = '\'';
			quotechar[1] = ' ';
		} else {
			i = 1;
			diffchar = '>';
			quotechar[0] = ' ';
			quotechar[1] = '\'';
		}

		for(; l<nlines[i]; l++) {
			/* We should check fgets() returning -1 on
			 * EOF or errors, but we did so above.
			 */
			dummy = getline(&line[i],&linesize[i],inputfile[i]);

			stripendline(line[i]);

			if ( strlen(line[i])>maxlinelen[i] ) {
				line[i][maxlinelen[i]+1] = 0;
				line[i][maxlinelen[i]] = '_';
			} else {
				line[i][strlen(line[i])+1] = 0;
				line[i][strlen(line[i])] = '\'';
			}

			line[1-i][0] = 0;

			fprintf(diffoutfile,formatstr,l+1,quotechar[0],line[0],
			                         diffchar,quotechar[1],line[1]);
		}
	}

	fclose(diffoutfile);
	fclose(inputfile[0]);
	fclose(inputfile[1]);
}
