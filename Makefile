INSTALLDIR = $(DESTDIR)

all:
	@echo "all"

clean:
	@echo "clean"

install:
	@echo "Make directory"
	mkdir -p $(INSTALLDIR)/var/local/cDistro
	mkdir -p $(INSTALLDIR)/etc/init.d/
	@echo "Install files"	
	install -m 0755 cdistro $(INSTALLDIR)/etc/init.d/
	install -m 0644 cdistro.conf $(INSTALLDIR)/etc/
	install -m 0700 cdistrod $(INSTALLDIR)/usr/sbin/ 
	cp -dR web/* $(INSTALLDIR)/var/local/cDistro/
