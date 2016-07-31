iote-web
========
- Internet of Things Express
- Production: [someurl.com](https://someurl.com)
- Staging: [staging.someurl.com](http://staging.someurl.com)
	- Do whatever you please on this dummy site

## API - Version 1
#### Client Access

- Every request to the API backend must be accompanied by a client token
- This should make it at least a little harder for hackers to spoof requests
- To do this, attach a header with the key of `X-Iote-Client-Token` to the request
- Ask someone like *max-pi* for the actual token value to attach to this header

#### User Authentication

- The back-end uses [Basic Access Authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) to identify users

#### API Responses

- Failed requests will return with 4xx status codes as usual
- Successful requests will return with 2xx status codes as usual
- All requests should always return a readable message explaining the result
- The expected data will always be returned through the `response` property
- An example of a response from searching for a user can be found here:
```json
{
	"message": "This currently active user",
	"response": [
		{
			"_id": "56b8f29a6961e283030041a8",
			"updated_at": "2016-02-10T08:24:24+0000",
			"created_at": "2016-02-08T19:55:06+0000",
			"emails": [
				"cheongwillie@gmail.com"
			],
			"phones": [
				"+12064130442"
			],
			"beacons": [
				"56b9ac246961e2ec430041a8",
				"56b9ac246961e2ec430041a9"
			],
			"metadata": []
		}
	]
}
```

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
	- Returns user object; note that this newly added contact must be verified using the code sent to the email/phone before it will be tagged to the user

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
