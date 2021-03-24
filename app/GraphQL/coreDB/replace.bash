#!/bin/bash

# these will process the standard output from amplify codegen into php 
# the code still needs to be pasted into the corresponding php files, as just js
# will work on automating this some more next

# must be run like this: bash replace.bash 
# will run on all files php files in the folder


sed -i -e 's/export const /public $/g' ./*.php

sed -i -e "s/\`/\'/g" ./*.php
