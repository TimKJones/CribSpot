class A2Cribs.Hotlist
  @Initialize: ->
    el = $('#friends-list')
    A2Cribs.HotlistObj = new A2Cribs.Hotlist(el)

  @GetUsers: (name) ->
    

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
  """
