cd ../..

# Normal test
php exakat init -p silly-tu -R https://github.com/mnapoli/silly.git -v

php exakat project -p silly-tu -v

# Normal call
php exakat analyze -p silly-tu -P Structures/ConcatEmpty -v

# Skip dependencies, in particular Complete/PropagateConstants which is already passed
php exakat analyze -p silly-tu -P Arrays/NoSpreadForHash -v -nodep

# Already done
php exakat analyze -p silly-tu -P Structures/ConcatEmpty -v -norefresh

# Dependency is already done, but we\'ll do it again
php exakat analyze -p silly-tu -P Type/Shellcommands -v -norefresh

php exakat remove -p silly-tu

cd -

