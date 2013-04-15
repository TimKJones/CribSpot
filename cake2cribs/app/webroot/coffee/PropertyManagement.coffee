class A2Cribs.PropertyManagement
    
    @removeSublet:(id)->
        alertify.confirm "Are you sure you want to delete this property? This can't be undone.", (e)=>
            if e
                # User clicked okay
                url = myBaseUrl + "sublets/remove/#{id}"
                window.location.href = url
            else
                return