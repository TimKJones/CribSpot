class A2Cribs.Hotlist
  @Initialize: ->
    el = $('#friends-list')
    A2Cribs.HotlistObj = new A2Cribs.Hotlist(el)

  @call: (friend, action) ->
    url = myBaseUrl + "friends/hotlist/#{action}"
    deferred = new $.Deferred()
    data = {
      friend_id: friend
    }
    $.ajax
      url: url
      data: data
      type: "POST"
      success: (response) =>
        deferred.resolve(JSON.parse response)
      error: =>
        deferred.reject()
    return deferred.promise()

  constructor: (@DOMRoot) ->
    @template = _.template(A2Cribs.Hotlist.templateHTML)
    @setup()

  setup: ->
    $.when(@get()).then(
      ((data, status, jqXHR) =>
        data = {
          friends: data
        }
        # @DOMRoot.html(@template(data))
        @render(data)
      ), 
      ((data, status, jqXHR) =>
        alert data
      ))
    return this

  render: (data) ->
    @DOMRoot.html(@template(data))
    $('.friend-adder .typeahead').typeahead({
      source: (query, process) ->
        if query.match(/^.+\@.+\..+$/)
          console.log encodeURIComponent query
          $.ajax
            type: 'get'
            url: myBaseUrl + 'users/getbyname'
            data: 
              name: query
            success: (data) ->
              response_data = JSON.parse data
              namelist = response_data.map((u) -> "#{u.User.email} - <em>#{u.User.first_name} #{u.User.last_name}</em> <img src='http://placehold.it/20x20'/>")
              process(namelist)
              console.log(namelist)
            fail: (data) ->
              console.log(data)
      updater: (item) ->
        item.split(' ')[0]

        # $.getJSON(myBaseUrl + 'users/getbyname', {name: query})
        #   .done((data)->
        #     process(data))
        #   .always((data)->
        #     console.log(data))
        
    })

  get: ->
    deferred = new $.Deferred()
    $.ajax 
      url: myBaseUrl + "friends/hotlist/"
      type:"GET"
      success: (data) =>
        deferred.resolve(JSON.parse data)
      error: =>
        deferred.reject()

    return deferred.promise()

  remove: (friend) ->
    $.when(A2Cribs.Hotlist.call(friend, 'remove')).then(
      ((data, status, jqXHR) =>
        data = {
          friends: data
        }
        @render(data)
      ))

  add: (friend) ->
    $.when(A2Cribs.Hotlist.call(friend, 'add')).then(
      ((data, status, jqXHR) =>
        data = {
          friends: data
        }
        @render(data)
      ))


  @templateHTML: """
  <div id='share-all'></div>
  <ul class='friends'>
    <% _.each(friends, function(elem, idx, list) { %>
      <li class='friend'>
        <%=elem.first_name%> <%=elem.last_name%> <a href = '#' onClick='A2Cribs.HotlistObj.remove(<%=elem.id%>)'>x</a>
      </li>
    <% }); %>
  </ul>
  <div class='friend-adder'>
      <input class='typeahead' type='text' autocomplete='off'></input>
  </div>
  """
