#!/bin/sh

## pre-commit script to prevent merge markers from being committed. 

changed=$(git diff --cached --name-only)

if [[ -z "$changed" ]]
then
    exit 0
fi

echo $changed | xargs egrep '[><]{7}' -H -I --line-number

## If the egrep command has any hits - echo a warning and exit with non-zero status.
if [ $? == 0 ]
then
    echo "\n\nWARNING: You have merge markers in the above files, lines. Fix them before committing.\n\n"
    exit 1    
fi