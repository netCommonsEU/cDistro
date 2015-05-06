INSTALLDIR = $(DESTDIR)

all:
	@echo "all"

clean:
	@echo "clean"

install:
	@echo "Creating directories"
	mkdir -p $(INSTALLDIR)/var/local/cDistro/
	mkdir -p $(INSTALLDIR)/etc/init.d/
	mkdir -p $(INSTALLDIR)/etc/cloudy/
	@echo "Installing files"
	install -m 0755 cdistro $(INSTALLDIR)/etc/init.d/
	install -m 0644 cloudy.conf $(INSTALLDIR)/etc/cloudy/
	install -m 0700 cdistrod $(INSTALLDIR)/usr/sbin/
	cp -dR web/* $(INSTALLDIR)/var/local/cDistro/

.PHONY: all clean install
