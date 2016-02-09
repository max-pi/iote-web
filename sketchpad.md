## For taking notes and references


Mongo cannot be accessed remotely from outside the droplet
The database should be safe as long as the droplet is secure
- vim /etc/mongodb.conf
- take out the `bind_ip` config to enable remote access


** Counting a collection from bash **
- echo "db.users.count()" | mongo iote


** Used to wipe mongodb from bash **
- echo "db.dropDatabase()" | mongo iote

