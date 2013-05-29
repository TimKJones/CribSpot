class A2Cribs.FeaturedListings

    # widget is a div where the featured listings for the supplied lat, lon
    # will be loaded into
    constructor: (@widget, @latitude, @longitude, @radius, callback=null)->
        
        url = "/FeaturedListings/getListings/#{latitude}/#{longitude}/#{radius}"
        @widget.load(url)

        






