# Commands expected to be pre-installed
COMPOSER_CMD=composer
PHIVE_CMD=phive
GPG_CMD=gpg
GIT_CMD=git

# Tools installed by this script
BEHAT_CMD=tools/behat
BOX_CMD=tools/box
PHPCS_CMD=tools/phpcs
PHPSTAN_CMD=tools/phpstan

# Setup
TARGET=byrolint.phar
DESTDIR=/usr/local/bin
VERSION=VERSION
SIGNATURE=${TARGET}.asc
SIGNATURE_ID=hannes.forsgard@fripost.org

SRC_FILES:=$(shell find src/ bin/ -type f)

.DEFAULT_GOAL=all

.PHONY: all clean

all: test analyze build check

clean:
	rm $(TARGET) --interactive=no -f
	rm $(VERSION) --interactive=no -f
	rm $(SIGNATURE) --interactive=no -f
	rm -rf vendor
	rm -rf tools

#
# Build and sign
#

.PHONY: build build_release sign

build:
	rm -rf $(VERSION)
	make $(TARGET)

build_release: all sign

$(TARGET): vendor/installed $(SRC_FILES) box.json $(VERSION) $(BOX_CMD)
	$(BOX_CMD) compile

sign: $(SIGNATURE)

$(SIGNATURE): $(TARGET)
	rm -rf $@
	$(GPG_CMD) -u $(SIGNATURE_ID) --detach-sign --output $@ $<

$(VERSION):
	-$(GIT_CMD) describe > $@

#
# Install/uninstall
#

.PHONY: install uninstall

install: $(TARGET)
	mkdir -p $(DESTDIR)
	cp $< $(DESTDIR)/`basename "$(TARGET)" .phar`

uninstall:
	rm -f $(DESTDIR)/`basename "$(TARGET)" .phar`

#
# Tests and analysis
#

.PHONY: analyze test check phpstan phpcs

analyze: phpstan phpcs

test: vendor/installed $(BEHAT_CMD)
	$(BEHAT_CMD) --stop-on-failure --suite=default

check: $(TARGET) $(BEHAT_CMD)
	$(BEHAT_CMD) --stop-on-failure --suite=phar

phpstan: vendor/installed $(PHPSTAN_CMD)
	$(PHPSTAN_CMD) analyze -l 8 src

phpcs: $(PHPCS_CMD)
	$(PHPCS_CMD)

#
# Dependencies
#

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor/installed: composer.lock
	$(COMPOSER_CMD) install
	touch $@

tools/installed: .phive/phars.xml
	$(PHIVE_CMD) install --force-accept-unsigned --trust-gpg-keys CF1A108D0E7AE720,31C7E470E2138192,0FD3A3029E470F86
	touch $@

$(BEHAT_CMD): tools/installed
$(BOX_CMD): tools/installed
$(PHPCS_CMD): tools/installed
$(PHPSTAN_CMD): tools/installed
