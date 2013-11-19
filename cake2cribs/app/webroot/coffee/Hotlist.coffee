class A2Cribs.Hotlist
  @Initialize: ->
    el = $('#hotlist')
    A2Cribs.HotlistObj = new A2Cribs.Hotlist(el)
    A2Cribs.HotlistObj.setup()

  call: (action, method, data) ->
    deferred = new $.Deferred()
    url = myBaseUrl + action

    $.ajax
      url: url
      data: data
      type: method
      success: (response) =>
        deferred.resolve(JSON.parse response)
      error: =>
        deferred.reject()
    return deferred.promise()

  constructor: (@DOMRoot) ->
    @topSection = _.template(A2Cribs.Hotlist.topSectionTemplate)
    @friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate)
    @friendsListPopup = _.template(A2Cribs.Hotlist.friendsListPopupTemplate)
    @expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate)
    @currentHotlist = @get()

    @sources = [{
      name: 'accounts'
      remote:
        url: myBaseUrl + 'users/getbyname?name=%QUERY'
        filter: (response) ->
          response.map (item) ->
            datum = 
              value: "#{item.User.email}" 
              name: "#{item.User.first_name} #{item.User.last_name}"
            return datum
    }]

    @setEditing false

  setup: ->
    @renderTopSection()
    @show()
    @renderBottomSection()
    A2Cribs.FeaturedListings.resizeHandler()

  #Initializer Functions

  renderTopSection: ->
    @DOMRoot.find('#top-section').html(@topSection())
    @DOMRoot.find('#title').show()
    @DOMRoot.find('#add-field').hide()
    @DOMRoot.find('#btn-add').hide()

    @DOMRoot.find('#add-field').typeahead(@sources)
    @DOMRoot.find('.twitter-typeahead').hide()

  renderFriendsList: (data) ->
    @DOMRoot.find('#friends').html(@friendsList data)
    @DOMRoot.find('#add-field').val("")
    @DOMRoot.find('.hotlist-remove-button').hide()
    @DOMRoot.find('li.friend span').tooltip()
    @DOMRoot.find('.friend-name').hide()

  renderBottomSection: ->
    @DOMRoot.find('#bottom-section').html(@expandButton())

  #Action functions

  getHotlistForPopup: (listing_id) ->
    @friendsListPopup { friends: @currentHotlist, listing_id: listing_id }

  get: ->
    $.when(@call('friends/hotlist', 'GET', null))
    .then (data) =>
      @currentHotlist = data
    .fail (data) =>
      console.log "ERROR in A2Cribs.HotlistObj.get(): ", data

  show: ->
    $.when(@call('friends/hotlist', 'GET', null))
    .then (data) =>
      @renderFriendsList { friends: data }
    .fail (data) =>
      console.log "ERROR in A2Cribs.HotlistObj.show(): ", data

  add: (friend) ->
    $.when @call('friends/hotlist/add', 'POST', { friend: friend })
    .then (data) =>
      @renderFriendsList { friends: data } 
      @expandForEdit()
      @currentHotlist = data
    .fail (data) =>
      console.log "ERROR: #{data}"

  remove: (friend) ->
    $.when @call('friends/hotlist/remove', 'POST', { friend: friend })
    .then (data) =>
      @renderFriendsList { friends: data } 
      @expandForEdit()
      @currentHotlist = data
    .fail (data) =>
      console.log "ERROR: #{data}"

  share: (listing, friend) ->
    console.log("sharing", listing, friend)
    $.when @call('friends/share', 'POST', {friend: friend, listing: listing})
    .then (data) =>
      if data.success is true
        A2Cribs.UIManager.Success()
      else
        A2Cribs.UIManager.Error()
    .fail (data) =>
        A2Cribs.UIManager.Error()
    .always (data, status, jqXHR) ->
      console.log data 

  #State functions

  retract: ->
    shows = [
      '.friend-abbr'
      '#title'
    ]

    hides = [
      '.hotlist-remove-button'
      '.friend-name'
      '#add-field'
      '.twitter-typeahead'
      '#btn-add'
    ]

    @DOMRoot.removeClass('expanded').removeClass('detailed')
    @DOMRoot.find('i').removeClass('icon-caret-up').addClass('icon-caret-down')

    @DOMRoot.find(shows.join(',')).show()
    @DOMRoot.find(hides.join(',')).hide()

    @DOMRoot.find('#btn-edit').removeClass('editing').html('Edit')

  expand: ->
    @DOMRoot.addClass('expanded')
    @DOMRoot.find('i').removeClass('icon-caret-down').addClass('icon-caret-up')

  expandForEdit: ->
    @expand()
    @DOMRoot.addClass('detailed')

    shows = [
      '.hotlist-remove-button'
      '.twitter-typeahead'
      '.friend-name'
      '#add-field'
      '#btn-add'
    ]

    hides = [
      '.friend-abbr'
      '#title'
    ]

    @DOMRoot.find(shows.join(',')).show()
    @DOMRoot.find(hides.join(',')).hide()

    @DOMRoot.find('#btn-edit').addClass('editing').html('Done')

  toggleEdit: ->
    if @isEditing()
      @setEditing false
      @retract()
    else
      @setEditing true
      @expandForEdit()

  toggleExpand: ->
    if $('#hotlist').hasClass('expanded')
      @retract()
    else
      @expand(false)

  isEditing: ->
    @DOMRoot.hasClass('editing')

  setEditing: (state) ->
    if state 
      @DOMRoot.addClass('editing')
    else
      @DOMRoot.removeClass('editing')

  # Templates

  @friendsListPopupTemplate: """
  <ul class="friends-popup">
    <% _.each(friends, function(elem, idx, list) { %>
      <li>
        <a href='#' onclick='A2Cribs.HotlistObj.share(<%=listing_id%>, <%=elem.id%>)'><%=elem.first_name%> <%=elem.last_name%></a>
      </li>
    <% }) %>
  </ul>
  """

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
