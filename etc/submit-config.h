// Generated from 'submit-config.h.in' on Thu Jan  9 10:15:58 EST 2014.

#ifndef _SUBMIT_CONFIG_
#define _SUBMIT_CONFIG_

/* Define the command to copy files to the user submit directory in the
   submit client differently under Windows and Linux */
#if defined(__CYGWIN__) || defined(__CYGWIN32__)
#define COPY_CMD "c:/cygwin/bin/cp"
#else
#define COPY_CMD "cp"
#endif

/* Submission methods and default. */
#define SUBMIT_DEFAULT    2
#define SUBMIT_ENABLE_CMD 0
#define SUBMIT_ENABLE_WEB 1

/* Default TCP port to use for command-line submit client/daemon. */
#define SUBMITPORT   9147

/* Team HOME subdirectory to use for storing temporary/log/etc. files
   and permissions. */
#define USERDIR      ".domjudge"
#define USERPERMDIR  0700
#define USERPERMFILE 0600

/* Last modified time in minutes after which to warn for submitting
   an old file. */
#define WARN_MTIME   5

#endif /* _SUBMIT_CONFIG_ */
