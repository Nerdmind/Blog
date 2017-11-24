import MySQLdb

db = MySQLdb.connect(host="localhost", user="root", passwd="swarming")
cur = db.cursor()

if cur.execute("SHOW DATABASES LIKE 'framework'") == 0:
	cur.execute("CREATE DATABASE framework")
