iote-web
========
- Internet of Things Express
- Production: [someurl.com](https://someurl.com)
- Staging: [staging.someurl.com](http://staging.someurl.com)
	- Do whatever you please on this dummy site

## API - Version 1
#### Endpoint prefix: `/`

- GET /beacon
	- AUTHENTICATION REQUIRED
	- Returns a list of beacons matching the query
	- Allowable query params are `id`, `batch`, `deleted`
	- `id` refers to the beaconId
	- `batch` refers to the batchId in which this beacon was created
	- `deleted` will include trashed beacon objects when set to any value
	- Returns an empty list if no query specified

- PUT /beacon/metadata/{beaconId}
	- AUTHENTICATION REQUIRED
	- Attaches arbitrary metadata to the beacon
	- Required param is `metadata`, an object that can be of any shape
	- Returns beacon object with the newly updated property

- POST /beacon/ping/{beaconId}
	- AUTHENTICATION REQUIRED
	- Stores coordinates to the beacon from a reporting user
	- Required param are `coordinates` and `appId`, both of which are strings
	- Returns beacon object with the newly updated property

- GET /user
	- AUTHENTICATION REQUIRED
	- Returns a list of users matching the query
	- Allowable query params are `id`, `contact`
	- Returns a list of the current user if no query specified

- PUT /user/metadata
	- AUTHENTICATION REQUIRED
	- Attaches arbitrary metadata to the current user
	- Required param is `metadata`, an object that can be of any shape
	- Returns user object with the newly updated property

- PUT /user/beacon/{"attach"|"detach"}
	- AUTHENTICATION REQUIRED
	- Attaches or detaches a beacon to the current user
	- Endpoint should be trailed with either `/attach` or `detach`
	- Required param is `beacon`, the id of the beacon to work with
	- Returns user object with the newly updated property

- POST /user/contact
	- AUTHENTICATION REQUIRED
	- Starts process for adding contact to current user
	- Required param is `contact` which can be either an email or phone number
	- Returns user object; note that the newly added contact must be verified, only then it will be tagged to the user

- POST /user/register
	- Creates new user object and sends verification messages
	- Required params are `contact` and `password`
	- Returns the newly created user object, which will be empty at this time

- POST /user/resend-verification-code
	- Resends verification mail for a contact
	- Required params are `contact` and `user`
	- The `user` param refers to the userId to resend the code for
	- Returns a null object

- POST /user/verify
	- Confirms an email or phone record for a user
	- Required params are `contact` and `code`
	- Returns the user object with its newly added contact
