#!/bin/bash

# This Source Code Form is subject to the terms of the Mozilla Public
# License, v. 2.0. If a copy of the MPL was not distributed with this
# file, You can obtain one at http://mozilla.org/MPL/2.0/.

__FILE__=`readlink -f $0`
__DIR__=`dirname $__FILE__`
cd $__DIR__

COMBINED=`basename $__FILE__ | cut -d'.' -f1`.js
touch $COMBINED
chown --reference=. $COMBINED
cat /dev/null > $COMBINED

SCRIPTS=( http://zeptojs.com/zepto.min.js \
http://underscorejs.org/underscore-min.js \
http://backbonejs.org/backbone-min.js )
for i in ${SCRIPTS[*]}
do
	script=`basename $i`
	curl $i > $script 2>/dev/null
	chown --reference=. $script
	cat $script >> $COMBINED
	echo ';' >> $COMBINED
done

sed -i '/sourceMappingURL/d' $COMBINED
cat baseline.js >> $COMBINED
