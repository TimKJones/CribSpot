#!/bin/bash

coffee -cj ../js/src/all.js ../coffee/A2Cribs.coffee ../coffee/Object.coffee ../coffee/MixPanel.coffee ../coffee/User.coffee ../coffee/Favorite.coffee ../coffee/Listing.coffee ../coffee/Marker.coffee ../coffee/FavoritesManager.coffee ../coffee/FacebookManager.coffee ../coffee/UtilityFunctions.coffee ../coffee/PhotoPicker.coffee ../coffee/ShareManager.coffee ../coffee/SmallBubble.coffee ../coffee/LargeBubble.coffee ../coffee/Cache.coffee ../coffee/Housemate.coffee ../coffee/UIManager.coffee ../coffee/Image.coffee ../coffee/Rental.coffee ../coffee/FilterManager.coffee ../coffee/RentalFilter.coffee ../coffee/FLDash.coffee ../coffee/Register.coffee ../coffee/Map.coffee ../coffee/MarkerModal.coffee ../coffee/UILayer/UILayer.coffee ../coffee/UILayer/Rentals.coffee ../coffee/UILayer/Fees.coffee ../coffee/QuickRental.coffee ../coffee/RentalSave.coffee ../coffee/MiniMap.coffee ../coffee/PageHeader.coffee ../coffee/ShoppingCart.coffee ../coffee/Landing.coffee ../coffee/Login.coffee ../coffee/FeaturedListings.coffee ../coffee/Order.coffee ../coffee/FullListing.coffee ../coffee/Dashboard.coffee ../coffee/Account.coffee ../coffee/Message.coffee ../coffee/PropertyManagement.coffee ../coffee/MobileFilter.coffee ../coffee/VerifyManager.coffee ../coffee/UserCache.coffee ../coffee/Tour.coffee ../coffee/Order.FeaturedListing.coffee ../coffee/Sublet.coffee ../coffee/SubletSave.coffee ../coffee/Geocoder.coffee ../coffee/Hotlist.coffee
java -jar compiler.jar --js ../js/src/all.js --js_output_file ../js/src/program.js
