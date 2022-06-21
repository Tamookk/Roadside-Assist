import sqlite3

# Get list of generated business names
with open("business_names.txt") as f:
    linelist = f.readlines()

con = sqlite3.connect("users.db")
cur = con.cursor()

usernames = []

for row in cur.execute("SELECT Email FROM Professional;"):
    usernames.append(row)

for i in range(0, len(usernames)):
    # Get business name
    business_name = linelist[i]
    print(usernames[i][0])

    # Insert business name into correct column of database
    sql = "UPDATE Professional SET BusinessName = ? WHERE Email = ?;"
    args = (business_name, usernames[i][0])
    cur.execute(sql, args)

# Close database connection
con.commit()
con.close()
print("Done!")
