import random
import sqlite3

# Dictionary of makes and models
cars = {
    'Ford': ['Focus', 'Mustang', 'Fiesta', 'Puma'],
    'Hyundai': ['i20', 'i30', 'Getz', 'Sonata'],
    'Nissan': ['Qashqai', 'Juke', 'X-Trail', 'Patrol'],
    'Toyota': ['Corolla', 'Yaris', 'RAV4', 'Camry']
}

# List of years
years = [x for x in range(1990, 2020)]

# Open user database to get list of registrations
con = sqlite3.connect("users.db")
cur = con.cursor()
registrations = []

for row in cur.execute("SELECT Registration FROM Customer"):
    registrations.append(row)

con.close()

# Randomly insert 100 records into car database
con = sqlite3.connect("vehicle.db")
cur = con.cursor()

for i in range(0, len(registrations)):
    registration = registrations[i][0]
    make = list(cars.keys())[random.randint(0, 3)]
    model = cars[make][random.randint(0, 3)]
    condition = 'Used'
    year = years[random.randint(0, len(years) - 1)]

    sql = "INSERT INTO Vehicle (Registration, Make, Model, Condition, Year) VALUES (?, ?, ?, ?, ?)"
    args = (registration, make, model, condition, year)
    print(args)
    cur.execute(sql, args)

# Close database connection
con.commit()
con.close()
print("Done!")
