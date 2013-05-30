cd neo4j
./bin/neo4j stop
rm -rf data
mkdir data
./bin/neo4j start
cd ..
sleep 1

