###
Class Analytics
Wrapper class to handle the interactions
with google analytics event tracking

**************************
Cribspot Events:
- Login
	- Logged in
	- Signed up
	- Login Required
- Filter
	- Changed
- Listing
	- Popup Opened
	- Go to website
	- View full page
- Full Page
	- Schedule Tour Clicked
	- Contact Owner Clicked
- Message
	- Sending Message
	- Message Sent
	- Message Failed
- Marker
	- Popup Opened
- Share
	- URL Copied 
	- Listing on FB
- Tour
- Post Rental
- Post Sublet
**************************
###


class A2Cribs.Analytics
	###
	Private Event Method
	Wrapper for the _trackEvent for google analytics
	###
	event = (category, action, label = null, value = null) ->
		_gaq.push ['_trackEvent', category, action, label, value]

	###
	Document Ready
	###
	$(document).ready =>
		$(document).on "track_event", (event, category, action, label = null, value = null) =>
			event category, action, label, value
