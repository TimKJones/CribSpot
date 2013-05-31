class A2Cribs.FeaturedListings

    # widget is a div where the featured listings for the supplied lat, lon
    # will be loaded into
    constructor: (@widget)->

    find: (up_lat, low_lat, up_long, low_long)->
        
        data = {
            'up_lat': up_lat,
            'low_lat': low_lat,
            'up_long': up_long,
            'low_long': low_long
        }
        url = "/FeaturedListings/getListings"
        $.get url, data, (response)=>
            @widget.find('.listings_list').html response    
 







