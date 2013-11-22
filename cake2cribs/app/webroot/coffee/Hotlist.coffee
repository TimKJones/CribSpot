(($) -> 
  $.fn.removeStyle = (style) ->
    search = new RegExp(style + '[^;]+;?', 'g')
    @each ->
      $(this).attr 'style', (i, style) ->
        style.replace(search, '') if style
)(jQuery)

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
      error: (response) =>
        deferred.reject(response)
    return deferred.promise()

  constructor: (@DOMRoot) ->
    @topSection = _.template(A2Cribs.Hotlist.topSectionTemplate)
    @friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate)
    @friendsListPopup = _.template(A2Cribs.Hotlist.friendsListPopupTemplate)
    @expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate)
    @currentHotlist = @get()

    @sources = [
      {
        name: 'test'
        local: ['hello', 'hellotest', 'hellotest2', 'hellotest3', 'hellotest4']
      },
      {
        name: 'accounts'
        remote:
          url: myBaseUrl + 'users/getbyname?name=%QUERY'
          filter: (response) ->
            response.map (item) ->
              datum = 
                value: "#{item.User.email}" 
                name: "#{item.User.first_name} #{item.User.last_name}"
              return datum
      }
    ]

    @setEditing false
    @isExpanded = false

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
    @DOMRoot.find('.btn-hotlist-remove').hide()
    @DOMRoot.find('li.friend span').tooltip()
    @DOMRoot.find('.friend-name').hide()

    @DOMRoot.find('li.friend').droppable
      accept: '.fl-sb-item, .large-bubble'
      hoverClass: 'drop-hover'
      tolerance: 'pointer'
      drop: (event, ui) ->
        console.log "shared", $(this).data('id'), ui.draggable.attr('listing_id') || ui.draggable.data('listing_id')
        ui.helper.hide()

    @DOMRoot.find('#share-all').droppable
      accept: '.fl-sb-item, .large-bubble'
      activeClass: 'drop-active'
      hoverClass: 'drop-hover'
      tolerance: 'pointer'
      drop: (event, ui) ->
        console.log "shared to all", ui.draggable.attr('listing_id') || ui.draggable.data('listing_id')
        ui.helper.hide()


    # @DOMRoot.droppable
    #   accept: '.fl-sb-item, .large-bubble'
    #   activeClass: 'expanded'
    @showOrHideExpandArrow()

  showOrHideExpandArrow: ->
    el = @DOMRoot.find('#bottom-section a')
    hotlistOnOneLine = @DOMRoot.find('ul.friends li:first').offset().top is @DOMRoot.find('ul.friends li:last').offset().top

    if @isExpanded or not hotlistOnOneLine
      el.show()
    else
      el.hide()

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
    $.when @call('invitations/invitefriends', 'POST', { emails: [friend] })
    .then (data) =>
      @call('friends/hotlist', 'GET', null)
    .then (data) =>
      @currentHotlist = data
      @renderFriendsList { friends: data }
      @expandForEdit()
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
      '.btn-hotlist-remove'
      '.friend-name'
      '#add-field'
      '.twitter-typeahead'
      '#btn-add'
    ]

    @DOMRoot.removeClass('expanded').removeClass('detailed')
    @DOMRoot.find('#expand-button i').removeClass('icon-caret-up').addClass('icon-caret-down')

    @DOMRoot.find(shows.join(',')).show()
    @DOMRoot.find(hides.join(',')).hide()

    @DOMRoot.find('#btn-edit').removeClass('editing').html('<i class="icon-edit"></i>')
    @DOMRoot.find('ul.friends').removeStyle('height')
    @setEditing false

    @isExpanded = false
    @showOrHideExpandArrow()

  expand: ->
    @DOMRoot.addClass('expanded')
    @DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up')

    @isExpanded = true
    @showOrHideExpandArrow()

    @setHeight()

  expandForEdit: ->
    @DOMRoot.addClass('expanded')
    @DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up')

    @isExpanded = true
    @showOrHideExpandArrow()

    @DOMRoot.addClass('detailed')

    @setHeight()

    shows = [
      '.btn-hotlist-remove'
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

  setHeight: ->
    a = @DOMRoot.find('ul.friends li:last-child')
    height = a.offset().top + a.height() - $('ul.friends').offset().top + 30
    @DOMRoot.find('ul.friends').height(height) if height < 300

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
  <div id='share-all'>
    <span class='title'>Hotlist</span>
    <span class='share-text'>Share to All</span>
  </div>
  <input class='typeahead' type='text' autocomplete='off' id='add-field'></input>
  <div id='buttons' class='pull-right'>
    <a href='#' data-toggle='popover' id='btn-add' class='btn-hotlist btn-hotlist-add' onClick="A2Cribs.HotlistObj.add($('#add-field').val())">+</a>
    <a href='#' id='btn-edit' class='btn-hotlist btn-hotlist-edit' onClick='A2Cribs.HotlistObj.toggleEdit()'><i class='icon-edit'></i></a>
  </div>
  """

  @friendsListTemplate: """
  <ul class='friends'>
    <% _.each(friends, function(elem, idx, list) { %>
      <li class='friend' data-id='<%=elem.id%>'>
        <% if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>
          <span class='friend-abbr' data-toggle='tooltip' title='<%=elem.first_name%> <%=elem.last_name%>'>
            <%=elem.first_name[0].toUpperCase()%><%=elem.last_name[0].toUpperCase()%> 
          </span>
        <% } else { %>
          <span class='friend-abbr' data-toggle='tooltip' title='<%=elem.email%>'>
            <%=elem.email[0]%>@<%=elem.email.split('@')[1][0]%>
          </span>
        <% } %>
        <span class='friend-name'>
          <% if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>
            <%=elem.first_name%> <%=elem.last_name%> 
          <% } else { %>
            <%=elem.email%> 
          <% } %>
        </span>
        <a class='btn-hotlist-remove btn-hotlist pull-right' href='#' onClick='A2Cribs.HotlistObj.remove(<%=elem.id%>)'><i class='icon icon-remove-circle'></i></a>
      </li>
    <% }); %>
  </ul>
  """

  @expandButtonTemplate: """
  <a href='#' onclick='A2Cribs.HotlistObj.toggleExpand()' id='expand-button'><i class='icon icon-caret-down'></i></a>
  """
