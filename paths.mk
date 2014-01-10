# Variables (mostly paths) set by configure.
# This file is globally included via Makefile.global.

# General package variables
PACKAGE = domjudge
VERSION = 4.0.0DEV
DISTNAME = $(PACKAGE)-$(VERSION)

# The following line is automatically modified by snapshot/release
# scripts, do not change manually!
PUBLISHED =

PACKAGE_NAME      = DOMjudge
PACKAGE_VERSION   = 4.0.0DEV
PACKAGE_STRING    = DOMjudge 4.0.0DEV
PACKAGE_TARNAME   = domjudge
PACKAGE_BUGREPORT = domjudge-devel@lists.A-Eskwadraat.nl

# Compilers and FLAGS
CC  = gcc
CXX = g++
CPP = gcc -E

CFLAGS   = -g -O2 -Wall -fstack-protector -fPIE -D_FORTIFY_SOURCE=2
CXXFLAGS = -g -O2 -Wall -fstack-protector -fPIE -D_FORTIFY_SOURCE=2
CPPFLAGS = 
LDFLAGS  =  -fPIE -pie -Wl,-z,relro -Wl,-z,now 

EXEEXT = 
OBJEXT = .o

# Other programs
LN_S    = ln -s
MKDIR_P = /bin/mkdir -p
INSTALL = /usr/bin/install -c


# Build checktestdata program?
CHECKTESTDATA_ENABLED = yes

# Use Linux cgroups?
USE_CGROUPS = 0

# Submit protocols
SUBMIT_DEFAULT    = 2
SUBMIT_ENABLE_CMD = 0
SUBMIT_ENABLE_WEB = 1

# libcgroup
LIBCGROUP = 

# libmagic
LIBMAGIC = 

# libJSONcpp
LIBJSONCPP = 

# libcURL
CURL_CFLAGS = 
CURL_LIBS   = -L/usr/lib/i386-linux-gnu -lcurl
CURL_STATIC = /usr/lib/i386-linux-gnu/libcurl.a -Wl,-Bsymbolic-functions -Wl,-z,relro -Wl,--as-needed -lidn -lrtmp -lgcrypt -lgnutls -Wl,-Bsymbolic-functions -Wl,-z,relro -lgssapi_krb5 -lkrb5 -lk5crypto -lcom_err -llber -llber -lldap -lz

# libboost
BOOST_CPPFLAGS  = -I/usr/include
BOOST_LDFLAGS   = -L/usr/lib
BOOST_REGEX_LIB = -lboost_regex-mt

# libgmpxx
LIBGMPXX = -lgmpxx -lgmp

# htpasswd
HTPASSWD = htpasswd

# User:group file ownership of password files
DOMJUDGE_USER   = root
WEBSERVER_GROUP = www-data

# Autoconf prefixes and paths
FHS_ENABLED    = no

prefix         = /home/codejam/domjudge
exec_prefix    = ${prefix}

bindir         = ${exec_prefix}/bin
sbindir        = ${exec_prefix}/sbin
libexecdir     = ${exec_prefix}/libexec
sysconfdir     = ${prefix}/etc
sharedstatedir = ${prefix}/com
localstatedir  = ${prefix}/var
libdir         = ${exec_prefix}/lib
includedir     = ${prefix}/include
oldincludedir  = /usr/include
datarootdir    = ${prefix}/share
datadir        = ${datarootdir}
infodir        = ${datarootdir}/info
localedir      = ${datarootdir}/locale
mandir         = ${datarootdir}/man
docdir         = ${datarootdir}/doc/${PACKAGE_TARNAME}
htmldir        = ${docdir}
dvidir         = ${docdir}
pdfdir         = ${docdir}
psdir          = ${docdir}

# Installation paths
domserver_bindir       = /home/codejam/domjudge/domserver/bin
domserver_etcdir       = /home/codejam/domjudge/domserver/etc
domserver_wwwdir       = /home/codejam/domjudge/domserver/www
domserver_sqldir       = /home/codejam/domjudge/domserver/sql
domserver_libdir       = /home/codejam/domjudge/domserver/lib
domserver_libextdir    = /home/codejam/domjudge/domserver/lib/ext
domserver_libwwwdir    = /home/codejam/domjudge/domserver/lib/www
domserver_libsubmitdir = /home/codejam/domjudge/domserver/lib/submit
domserver_logdir       = /home/codejam/domjudge/domserver/log
domserver_rundir       = /home/codejam/domjudge/domserver/run
domserver_tmpdir       = /home/codejam/domjudge/domserver/tmp
domserver_submitdir    = /home/codejam/domjudge/domserver/submissions

judgehost_bindir       = /home/codejam/domjudge/judgehost/bin
judgehost_etcdir       = /home/codejam/domjudge/judgehost/etc
judgehost_libdir       = /home/codejam/domjudge/judgehost/lib
judgehost_libjudgedir  = /home/codejam/domjudge/judgehost/lib/judge
judgehost_logdir       = /home/codejam/domjudge/judgehost/log
judgehost_rundir       = /home/codejam/domjudge/judgehost/run
judgehost_tmpdir       = /home/codejam/domjudge/judgehost/tmp
judgehost_judgedir     = /home/codejam/domjudge/judgehost/judgings

domjudge_docdir        = /home/codejam/domjudge/doc

domserver_dirs = $(domserver_bindir) $(domserver_etcdir) $(domserver_wwwdir) \
                 $(domserver_libdir) $(domserver_libsubmitdir) $(domserver_libextdir) \
                 $(domserver_libwwwdir) $(domserver_logdir) $(domserver_rundir) \
                 $(domserver_tmpdir) $(domserver_submitdir) $(domserver_sqldir)/upgrade \
                 $(addprefix $(domserver_wwwdir)/images/,affiliations countries teams) \
                 $(addprefix $(domserver_wwwdir)/,public team jury plugin api js)

judgehost_dirs = $(judgehost_bindir) $(judgehost_etcdir) $(judgehost_libdir) \
                 $(judgehost_libjudgedir) $(judgehost_logdir) $(judgehost_rundir) \
                 $(judgehost_tmpdir) $(judgehost_judgedir)

docs_dirs      = $(addprefix $(domjudge_docdir)/,admin judge team examples logos)

# Macro to substitute configure variables.
# Defined in makefile to allow for expansion of ${prefix} etc. during
# build and install, conforming to the GNU coding standards, see:
# http://www.gnu.org/software/hello/manual/autoconf/Installation-Directory-Variables.html
define substconfigvars
@[ -n "$(QUIET)" ] || echo "Substituting configure variables in '$@'."
@cat $< | sed \
	-e "s|@configure_input[@]|Generated from '$<' on `date`.|g" \
	-e 's,@DOMJUDGE_VERSION[@],4.0.0DEV,g' \
	-e 's,@domserver_bindir[@],/home/codejam/domjudge/domserver/bin,g' \
	-e 's,@domserver_etcdir[@],/home/codejam/domjudge/domserver/etc,g' \
	-e 's,@domserver_wwwdir[@],/home/codejam/domjudge/domserver/www,g' \
	-e 's,@domserver_sqldir[@],/home/codejam/domjudge/domserver/sql,g' \
	-e 's,@domserver_libdir[@],/home/codejam/domjudge/domserver/lib,g' \
	-e 's,@domserver_libextdir[@],/home/codejam/domjudge/domserver/lib/ext,g' \
	-e 's,@domserver_libwwwdir[@],/home/codejam/domjudge/domserver/lib/www,g' \
	-e 's,@domserver_libsubmitdir[@],/home/codejam/domjudge/domserver/lib/submit,g' \
	-e 's,@domserver_logdir[@],/home/codejam/domjudge/domserver/log,g' \
	-e 's,@domserver_rundir[@],/home/codejam/domjudge/domserver/run,g' \
	-e 's,@domserver_tmpdir[@],/home/codejam/domjudge/domserver/tmp,g' \
	-e 's,@domserver_submitdir[@],/home/codejam/domjudge/domserver/submissions,g' \
	-e 's,@judgehost_bindir[@],/home/codejam/domjudge/judgehost/bin,g' \
	-e 's,@judgehost_etcdir[@],/home/codejam/domjudge/judgehost/etc,g' \
	-e 's,@judgehost_libdir[@],/home/codejam/domjudge/judgehost/lib,g' \
	-e 's,@judgehost_libjudgedir[@],/home/codejam/domjudge/judgehost/lib/judge,g' \
	-e 's,@judgehost_logdir[@],/home/codejam/domjudge/judgehost/log,g' \
	-e 's,@judgehost_rundir[@],/home/codejam/domjudge/judgehost/run,g' \
	-e 's,@judgehost_tmpdir[@],/home/codejam/domjudge/judgehost/tmp,g' \
	-e 's,@judgehost_judgedir[@],/home/codejam/domjudge/judgehost/judgings,g' \
	-e 's,@domjudge_docdir[@],/home/codejam/domjudge/doc,g' \
	-e 's,@DOMJUDGE_USER[@],root,g' \
	-e 's,@WEBSERVER_GROUP[@],www-data,g' \
	-e 's,@BEEP[@],@BEEP@,g' \
	-e 's,@RUNUSER[@],domjudge-run,g' \
	-e 's,@USE_CGROUPS[@],0,g' \
	-e 's,@SUBMIT_DEFAULT[@],2,g' \
	-e 's,@SUBMIT_ENABLE_CMD[@],0,g' \
	-e 's,@SUBMIT_ENABLE_WEB[@],1,g' \
	> $@
endef
