class A2Cribs.Hotlist
  @Initialize: ->
    el = $('#hotlist')
    A2Cribs.HotlistObj = new A2Cribs.Hotlist(el)

  @call: (friend, action) ->
    url = myBaseUrl + "friends/hotlist/#{action}"
    deferred = new $.Deferred()
    data = {
      friend: friend
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

  @share: (listing, friend) ->
    deferred = new $.Deferred()
    $.ajax
      url: myBaseUrl + "friends/share"
      data: {
        friend: friend
        listing: listing
      }
      type: "POST"
      success: (data) =>
        deferred.resolve(JSON.parse data)
      error: (response) =>
        deferred.reject(response)

  constructor: (@DOMRoot) ->
    @topSection = _.template(A2Cribs.Hotlist.topSectionTemplate)
    @friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate)
    @expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate)

    @setup()

  setup: ->
    $.when(@get()).then(
      ((data, status, jqXHR) =>
        data = {
          friends: data
        }
        @render(data)
      ), 
      ((data, status, jqXHR) =>
        alert data
      ))
    return this

  render: (data) ->
    @DOMRoot.find('#top-section').html(@topSection(data))
    @DOMRoot.find('#friends').html(@friendsList(data))
    @DOMRoot.find('#bottom-section').html(@expandButton(data))

    $('#add-field').typeahead([
      {
        name: 'accounts',
        remote:
          url: myBaseUrl + 'users/getbyname?name=%QUERY'
          filter: (response) ->
            response.map (item) ->
              datum = 
                value: "#{item.User.email}" 
                name: "#{item.User.first_name} #{item.User.last_name}"
              return datum
      }
    ])

    $('.hotlist-remove-button').toggle()
    $('li.friend span').tooltip()

    @DOMRoot.find('#title').show()
    @DOMRoot.find('#add-field').hide()
    @DOMRoot.find('.twitter-typeahead').hide()
    @DOMRoot.find('#btn-add').hide()
    @DOMRoot.find('.friend-name').hide()

    A2Cribs.FeaturedListings.resizeHandler()

  get: ->
    deferred = new $.Deferred()
    $.ajax 
      url: myBaseUrl + "friends/hotlist/"
      type: "GET"
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
    $.when(A2Cribs.Hotlist.call(friend, 'add'))
    .then((data, status, jqXHR) =>
      data = {
        friends: data
      }
      @render(data)
    )
    .fail((data, status, jqXHR) =>
      console.log("ERROR: #{data}")
    )

  share: (listing, friend) ->
    $.when(A2Cribs.Hotlist.share(listing, friend))
    .always((data, status, jqXHR) ->
      console.log data )

  retract: ->
    $('#btn-edit').removeClass('editing')
    $('.hotlist-remove-button').hide()
    $('#hotlist').removeClass('expanded').removeClass('detailed')
    $('#hotlist i').removeClass('icon-caret-up').addClass('icon-caret-down')

    @DOMRoot.find('.friend-name').hide()
    @DOMRoot.find('.friend-abbr').show()

  expand: (detail) ->
    $('#hotlist').addClass('expanded')
    $('#hotlist').addClass('detailed') if detail
    $('#hotlist i').removeClass('icon-caret-down').addClass('icon-caret-up')

  toggleEdit: ->
    if $('#btn-edit').hasClass('editing')
      @DOMRoot.find('#title').show()
      @DOMRoot.find('#add-field').hide()
      @DOMRoot.find('.twitter-typeahead').hide()
      @DOMRoot.find('#btn-add').hide()
      @DOMRoot.find('#btn-edit').html('Edit')
      @retract()

    else
      @DOMRoot.find('#btn-edit').addClass('editing')
      @DOMRoot.find('.hotlist-remove-button').show()
      @DOMRoot.find('.friend-name').show()
      @DOMRoot.find('.friend-abbr').hide()
      @DOMRoot.find('#title').hide()
      @DOMRoot.find('#add-field').show()
      @DOMRoot.find('.twitter-typeahead').show()
      @DOMRoot.find('#btn-add').show()
      @DOMRoot.find('#btn-edit').html('Cancel')
      @expand(true)

  toggleExpand: ->
    if $('#hotlist').hasClass('expanded')
      @retract()
    else
      @expand(false)

  @topSectionTemplate: """
  <div id='title'>Hotlist</div>
  <div id='share-all'></div>
  <input class='typeahead' type='text' autocomplete='off' id='add-field'></input>
  <div id='buttons' class='pull-right'>
    <a href='#' data-toggle='popover' id='btn-add' class='btn btn-success' onClick="A2Cribs.HotlistObj.add($('#add-field').val())">+</a>
    <a href='#' id='btn-edit' class='btn btn-primary' onClick='A2Cribs.HotlistObj.toggleEdit()'>Edit</a>
  </div>
  """

  @friendsListTemplate: """
  <ul class='friends'>
    <% _.each(friends, function(elem, idx, list) { %>
      <li class='friend'>
        <span class='friend-abbr' data-toggle='tooltip' title='<%=elem.first_name%> <%=elem.last_name%>'>
          <%=elem.first_name[0].toUpperCase()%><%=elem.last_name[0].toUpperCase()%> 
        </span>
        <span class='friend-name'>
          <%=elem.first_name%> <%=elem.last_name%> 
        </span>
        <a class='hotlist-remove-button btn btn-danger pull-right' href='#' onClick='A2Cribs.HotlistObj.remove(<%=elem.id%>)'>Remove</a>
      </li>
    <% }); %>
  </ul>
  """

  @expandButtonTemplate: """
  <a href='#' onclick='A2Cribs.HotlistObj.toggleExpand()' id='expand-button'><i class='icon icon-caret-down'></i></a>
  """
