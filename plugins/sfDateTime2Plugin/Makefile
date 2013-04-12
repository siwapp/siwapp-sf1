# jme- ultra raw Makefile to generate the plugin

all: clean package

PLUGIN_NAME=$(shell basename `pwd`)

package:
	../../symfony plugin:package $(PLUGIN_NAME)

clean:
	rm -f $(PLUGIN_NAME)-[0-9]*.[0-9]*.[0-9]*.tgz