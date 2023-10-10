
# RXMG Practical Code Test Solution

Code has been added to address the problem identified below.  Thought process is provided below the problem statement in the `Solution` section. Do also review the `Notes` section.

# Problem to Solve

We needed to get the results from an external API every 24 hours.  That data was to be saved to a file and added into our database. An local API endpoint was also needed to be able to pull the data from our database.

# Solution

- Updated .env to hold MySQL info, added .env to .gitignore for security
- Added migration to hold data from API
- Added artisan command `PullStaff` for either cron or Laravel Scheduler to pull data from API and save to DB/CSV.  
- Added unsecured API endpoint to output JSON of all staff (Can be accessed via /api/staff)


## Notes
Reference was made in original README.MD to those in the external API as "users", so it was unclear whether to create users with their info. External API did not have email, username, password, etc.  So an educated assumption was made that they were not literal users.  Code was written accordingly. 

Original instructions said to:
1. Put data into CSV
2. Put data into database

In that specific order, although nothing was explicitly said that said it had to be in that order.

I figured if the external endpoint updated any of their data but retained the same data for the id's, it'd be easier to just sync our database to their data and then select from our database and dump to CSV.