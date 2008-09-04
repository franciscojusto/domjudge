###############################
# Restrictions during testing #
###############################

# User under which to run solutions (ID or name)
RUNUSER=domjudge-run

# Run solutions in a chroot environment? (gives better security)
USE_CHROOT=1

# Optional script to run for creating/destroying chroot environment,
# leave empty to disable. The default script can be used to support
# Sun Java with a chroot (edit the script first!).
CHROOT_SCRIPT=chroot-startstop.sh

# Maximum seconds available for compiling
COMPILETIME=30

# Maximum size of solution source code in kB
# This must not be greater than the submission.sourcecode field
# in the database (normally MEDIUMBLOB = 16MB)
SOURCESIZE=256

# Maximum memory usage by RUNUSER in kB
# This includes the shell which starts the compiled solution and 
# also any interpreter like Sun 'java', which takes 200MB away!
MEMLIMIT=524288

# Maximum filesize RUNUSER may write in kB
# This should be greater than the maximum testdata output, otherwise
# solutions will abort before writing the complete correct output!!
FILELIMIT=4096

# Maximum no. processes running as RUNUSER (including shell and
# possibly interpreters)
PROCLIMIT=8


# Loglevels (as defined in syslog.h)
LOG_EMERG=0
LOG_ALERT=1
LOG_CRIT=2
LOG_ERR=3
LOG_WARNING=4
LOG_NOTICE=5
LOG_INFO=6
LOG_DEBUG=7

# Exit/error codes:
E_CORRECT=0
E_COMPILE=101
E_TIMELIMIT=102
E_RUNERROR=103
E_OUTPUT=104
E_ANSWER=105
E_PRESENTATION=106
E_MEMORYLIMIT=107
E_OUTPUTLIMIT=108
E_INTERN=127 # Internal script error
