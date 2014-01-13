/* Generated from 'runguard-config.h.in' on Mon Jan 13 10:26:42 EST 2014. */

#ifndef _RUNGUARD_CONFIG_
#define _RUNGUARD_CONFIG_

/* Lots of suffixed names because we want to support multiple judgedaemons per host.  This allows 8 of them.*/
#define VALID_USERS "domjudge-run,domjudge-run-0,domjudge-run-1,domjudge-run-2,domjudge-run-3,domjudge-run-4,domjudge-run-5,domjudge-run-6,domjudge-run-7"

#define CHROOT_PREFIX "/home/rui/domjudge/output/judging"

#define USE_CGROUPS 0

#endif /* _RUNGUARD_CONFIG_ */
