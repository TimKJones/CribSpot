(($) -> 
  $.fn.removeStyle = (style) ->
    search = new RegExp(style + '[^;]+;?', 'g')
    @each ->
      $(this).attr 'style', (i, style) ->
        style.replace(search, '') if style

  $.fn.animateHighlight = (highlightColor, duration) ->
    highlightBg = highlightColor || "#FFFFFF"
    animateMs = duration || 500
    originalBg = this.css("backgroundColor")
    originalColor = this.css('color')
    this.stop()
      .css("background-color", highlightBg)
      .css('color', highlightBg)
      .animate({
        backgroundColor: originalBg
        color: originalColor
      }, animateMs)

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
        try
          deferred.resolve(JSON.parse response)
        catch error
          deferred.reject(response)
      error: (response) =>
        deferred.reject(response)
    return deferred.promise()

  constructor: (@DOMRoot) ->
    @topSection = _.template(A2Cribs.Hotlist.topSectionTemplate)
    @friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate)
    @notLoggedIn = _.template(A2Cribs.Hotlist.notLoggedInTemplate)
    @friendsListPopup = _.template(A2Cribs.Hotlist.friendsListPopupTemplate)
    @expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate)

    @sources = [
      {
        name: 'accounts'
        remote:
          url: myBaseUrl + 'users/getbyname?name=%QUERY'
          filter: (response) ->
            response.map (item) ->
              return {
                value: item.User.email 
                name: "#{item.User.first_name} #{item.User.last_name}"
              }
      }
    ]

    @setEditing false
    @isExpanded = false

  handleFBLoad: ->
    @sources.push
      name: 'facebook-friends'
      prefetch: 
        url: "https://graph.facebook.com/me/friends?access_token=#{FB.getAccessToken()}&fields=id,name,picture,first_name,last_name"
        ttl: 0
        filter: (response) ->
          # console.log(response)
          response.data.map (item) ->
            return {
              value: item.name
              tokens: item.name.split(' ')
              facebook_id: item.id
              picture: item.picture.data.url
              first_name: item.first_name
              last_name: item.last_name
            }

    @DOMRoot.find('#add-field')
      .typeahead(@sources)
      .on('typeahead:selected', (e, d, ds) => 
        @setAddIdField e, d, ds
      )
      .on('typeahead:autocompleted', (e, d, ds) => 
        @setAddIdField e, d, ds
      )
      .on('typeahead:hinted', (e, d, ds) => 
        @setAddIdField e, d, ds
      )
      .bind('change cut paste keyup', ->
        $(this).removeData('friend') if $(this).val() is '' 
      )

  setAddIdField: (event, datum, dataset) ->
    # console.log( @DOMRoot.find('#friend-add-id').val() )
    name = datum.value.replace(/^\s+|\s+$/g, "").toLowerCase()
    val = $('#add-field').val().replace(/^\s+|\s+$/g, "").toLowerCase()
    if name is val
      @DOMRoot.find('#add-field').data('friend', datum)
    else
      @DOMRoot.find('#add-field').removeData('friend')

  setup: ->
    $(document).on "checked_logged_in logged_in", (event) =>
      logged_in = A2Cribs.Login?.logged_in
      @renderTopSection(logged_in)
      @show()
      @renderBottomSection()
      @currentHotlist = @get()
      @setHeight(true)

  #Initializer Functions

  setupDroppables: ->
    @DOMRoot.find('li.friend').droppable
      accept: '.fl-sb-item, .large-bubble'
      hoverClass: 'drop-hover'
      tolerance: 'pointer'
      drop: (event, ui) ->
        listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id')
        if $(this).data('facebook_id')
          A2Cribs.HotlistObj.shareToFB(listing_id, $(this).data('facebook_id'))
        else
          A2Cribs.HotlistObj.shareToEmail(listing_id, $(this).data('email'))
        $(this).find('.friend-abbr').animateHighlight()
        ui.helper.hide()
        
    @DOMRoot.find('ul.friends.no-friends').droppable
      accept: '.fl-sb-item, .large-bubble'
      hoverClass: 'drop-hover'
      tolerance: 'pointer'
      drop: (event, ui) ->
        listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id')
        ui.helper.hide()

        FB.ui({
          method: 'send'
          link: "http://www.cribspot.com/listing/#{listing_id}"
          name: "Share this listing"
        },(response)->
          # console.log response
        )

    # @DOMRoot.find('#share-all').droppable
    #   accept: '.fl-sb-item, .large-bubble'
    #   activeClass: 'drop-active'
    #   hoverClass: 'drop-hover'
    #   tolerance: 'pointer'
    #   drop: (event, ui) =>
    #     @shareToAll(event, ui)
    #     ui.helper.hide()

  destroyDroppables: ->
    @DOMRoot.find('li.friend').droppable("destroy")
    @DOMRoot.find('ul.friends.no-friends').droppable("destroy")

  renderTopSection: (logged_in) ->
    @DOMRoot.find('#top-section').html(@topSection({loggedIn: logged_in}))
    @DOMRoot.find('#title').show()
    @DOMRoot.find('#add-field').hide()
    @DOMRoot.find('#btn-add').hide()

    # @DOMRoot.find('#add-field').typeahead(@sources)

    $.when(window.fbInit).then( => 
      FB.getLoginStatus (response) =>
        if response.status is 'connected'
          @handleFBLoad()
    )

    @DOMRoot.find('.twitter-typeahead').hide()

    @DOMRoot.find('#link-info').popover
      title: 'What is this?'
      content: "You can share listings with your friends!<br/>Either click the <i class='icon-user'></i> icon on a listing or drag the listing to one of your friends on the hotlist."
      html: true
      placement: 'bottom'

    $("#add-field").keyup (event) ->
      $("#btn-add").click() if event.keyCode is 13

  renderFriendsList: (data) ->
    if A2Cribs.Login.logged_in
      @DOMRoot.find('#friends').html(@friendsList data)
      @DOMRoot.find('#add-field').val("")
      @DOMRoot.find('.tt-hint').val("")
      @DOMRoot.find('.btn-hotlist-remove').hide()
      @DOMRoot.find('.friend-name').hide()

      $(document).on 'mousedown mouseup','.grab, .grabbing', (event) ->
        $(this).toggleClass('grab').toggleClass('grabbing')

      @setupDroppables()

      @DOMRoot.find('li.friend').tooltip({
        animated : 'fade',
        container: 'body'
      });

      # @DOMRoot.droppable
      #   accept: '.fl-sb-item, .large-bubble'
      #   activeClass: 'expanded'
    else
      @DOMRoot.find("#friends").html(@notLoggedIn())

    @setHeight(true)

  startedDragging: ->
    if A2Cribs.Login?.logged_in
      @retract()
      @expand()

  stoppedDragging: ->
    if A2Cribs.Login?.logged_in
      @retract()

  shareToAll: (event, ui) ->
    listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id')
    fb_ids = $('ul.friends li').map (i) ->
      a = $(this).data('facebook_id')
      return a

    # console.log fb_ids, fb_ids.length

    # FB.ui({
    #   method: 'send'
    #   link: "http://www.cribspot.com/listing/#{listing_id}"
    #   to: fb_ids
    # })


  renderBottomSection: ->
    @DOMRoot.find('#bottom-section').html(@expandButton())

  #Action functions

  getHotlistForPopup: (listing_id) ->
    @friendsListPopup { friends: @currentHotlist, listing_id: listing_id }

  get: ->
    if A2Cribs.Login.logged_in
      $.when(@call('friends/hotlist', 'GET', null))
      .then (data) =>
        @currentHotlist = data
      .fail (data) =>
        # console.log "ERROR in A2Cribs.HotlistObj.get(): ", data

  show: ->
    if A2Cribs.Login.logged_in
      $.when(@call('friends/hotlist', 'GET', null))
      .then (data) =>
        @renderFriendsList { friends: data }
      .fail (data) =>
        # console.log "ERROR in A2Cribs.HotlistObj.show(): ", data
    else
      @renderFriendsList null

  add: (friend) ->
    if A2Cribs.Login.logged_in
      if $('#add-field').data('friend')?.facebook_id?
        route = 'invitations/invitefbfriend'
        postdata = { friend: $('#add-field').data('friend') }

        @showFBAddMessageModal($('#add-field').data('friend').facebook_id)
      else
        route = 'invitations/invitefriends'
        postdata = { emails: [$('#add-field').val()] }

      $.when @call(route, 'POST', postdata)
      .then (data) =>
        @call('friends/hotlist', 'GET', null)
      .then (data) =>
        @currentHotlist = data
        @renderFriendsList { friends: data }
        @expandForEdit()
      .fail (data) =>
        # console.log "ERROR: #{data}"

  showFBAddMessageModal: (friend) ->
    FB.ui({
      method: 'send'
      link: 'http://www.cribspot.com'
      to: friend
    })

  remove: (friend) ->
    if A2Cribs.Login.logged_in
      $.when @call('friends/hotlist/remove', 'POST', { friend: friend })
      .then (data) =>
        @renderFriendsList { friends: data } 
        @expandForEdit()
        @currentHotlist = data
      .fail (data) =>
        # console.log "ERROR: #{data}"

  share: (listing, friend) ->
    if A2Cribs.Login.logged_in
      # console.log("sharing", listing, friend)
      $.when @call('friends/share', 'POST', {friend: friend, listing: listing})
      .then (data) =>
        if data.success is true
          A2Cribs.UIManager.Success("Successfully Shared Listing")
        else
          A2Cribs.UIManager.Error("There was a problem sharing the listing.")
      .fail (data) =>
          A2Cribs.UIManager.Error("There was a problem sharing the listing.")
      # .always (data, status, jqXHR) ->
      #   console.log data 

  shareToEmail: (listing, friend) ->
    # console.log("sharing", listing, friend)
    $.when @call('invitations/inviteFriends', 'POST', {emails: [friend], listing: listing})
    .then (data) =>
      if data.success is true
        A2Cribs.UIManager.Success("Successfully Shared Listing")
      else
        A2Cribs.UIManager.Error("There was a problem sharing the listing.")
    .fail (data) =>
        A2Cribs.UIManager.Error("There was a problem sharing the listing.")
    .always (data, status, jqXHR) ->
      $('#share-to-email').val("")
      # console.log data 

  shareToFB: (listing, facebook_id) ->
    FB.ui({
      method: 'send'
      link: "http://www.cribspot.com/listing/#{listing}"
      to: facebook_id 
      name: "Share this listing"
    })


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
      '.tt-hint'
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
    @setHeight(true)

    @setupDroppables()

    @DOMRoot.find('li.friend').tooltip({
      animated : 'fade',
      container: 'body'
    });

  expand: ->
    @DOMRoot.addClass('expanded')
    @DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up')

    @isExpanded = true

    @setHeight()

  expandForEdit: ->
    @DOMRoot.addClass('expanded')
    @DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up')

    @isExpanded = true

    @DOMRoot.addClass('detailed')

    shows = [
      '.btn-hotlist-remove'
      '.twitter-typeahead'
      '.tt-hint'
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

    @DOMRoot.find('li.friend').tooltip("destroy")

    @destroyDroppables()

    @setHeight(false, true)

  showOrHideExpandArrow: ->
    el = @DOMRoot.find('#bottom-section a')
    if @DOMRoot.find('ul.friends li').length
      hotlistOnOneLine = @DOMRoot.find('ul.friends li:first').offset().top is @DOMRoot.find('ul.friends li:last').offset().top
    else
      hotlistOnOneLine = true

    if not A2Cribs.Login?.logged_in
      el.hide()
      return

    if @isExpanded or not hotlistOnOneLine
      el.show()
    else
      el.hide()

  setHeight: (retract = false, max = false) ->
    @showOrHideExpandArrow()

    if retract
      a = @DOMRoot.find('ul.friends li:first-child')
    else
      a = @DOMRoot.find('ul.friends li:last-child') 

    if a.length
      height = a.offset().top + a.height() - $('ul.friends').offset().top
    else
      height = 0

    if height <= 10
      height = 70
    # else
    #   height = height + 30

    if $('#bottom-section a').is(":visible")
      height = height + $('#bottom-section a').height() + 20

    if not A2Cribs.Login?.logged_in
      height = height + 25

    if height < 300 or not max
      @DOMRoot.find('ul.friends').height(height)
    else
      @DOMRoot.find('ul.friends').height(300)

    @DOMRoot.find('ul.friends').on 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', =>
      A2Cribs.FeaturedListings.resizeHandler()

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
  <div id='shareto'>
    <input type='email' id='share-to-email' placeholder='to email'></input>
    <a class='share-to-email-btn' href='#' onClick='A2Cribs.HotlistObj.shareToEmail(<%=listing_id%>, $("#share-to-email").val());'>
      <i class="icon-share"></i>
    </a>
  </div>
  <ul class="friends-popup">
    <% _.each(friends, function(elem, idx, list) { %>
      <li>
        <% name = elem.first_name ? elem.first_name + ' ' + elem.last_name : elem.email %>
        <% if(elem.facebook_id) { %>
          <a href='#' onclick='A2Cribs.HotlistObj.shareToFB(<%=listing_id%>, <%=elem.facebook_id%>)'><%=name%></a>
        <% } else { %>
          <a href='#' onclick='A2Cribs.HotlistObj.share(<%=listing_id%>, <%=elem.id%>)'><%=name%></a>
        <% } %>
      </li>
    <% }) %>
  </ul>
  """

  @topSectionTemplate: """
  <div id='share-all'>
    <span class='title'>Share with your Friends <a title='What is this?' href='#' id='link-info' class='icon icon-info-sign'></a></span>
    <span class='share-text'>Share to All</span>
  </div>
  <input class='typeahead' type='text' autocomplete='off' id='add-field'></input>
  <div id='buttons' class='pull-right <%=loggedIn ? "" : "hide"%>'>
    <a href='#' data-toggle='popover' id='btn-add' class='btn-hotlist btn-hotlist-add' onClick="A2Cribs.HotlistObj.add($('#add-field').val())">+</a>
    <a href='#' id='btn-edit' class='btn-hotlist btn-hotlist-edit' onClick='A2Cribs.HotlistObj.toggleEdit()'><i class='icon-edit'></i></a>
  </div>
  <div style='clear: both;'></div>
  """

  @friendsListTemplate: """
  <ul class='friends <%=friends.length ? "has-friends" : "no-friends"%>'>
    <% if(friends.length) { %>
    <% _.each(friends, function(elem, idx, list) { %>
      <% 
        var tooltitle = elem.email 
        if (elem.first_name) {
          tooltitle = elem.first_name + ' ' + elem.last_name
        }
      %> 
      <li class='friend' data-id='<%=elem.id%>' data-toggle='tooltip' title='<%=tooltitle%>'' data-facebook_id='<%=elem.facebook_id || null%>' data-email='<%=elem.email%>'>
        <% if (elem.facebook_id){ %>
          <img class='friend-abbr hotlist-profile-img' src='https://graph.facebook.com/<%=elem.facebook_id%>/picture?width=80&height=80'></img>
        <% } else if (elem.profile_img) { %>
          <img class='friend-abbr otlist-profile-img' src='<%=elem.profile_img%>'></img>
        <% } else if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>
          <span class='friend-abbr'>
            <%=elem.first_name[0].toUpperCase()%><%=elem.last_name[0].toUpperCase()%> 
          </span>
        <% } else { %>
          <span class='friend-abbr'>
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
    <% } else { %>
      <li class='add-friends-notice'>No friends added yet.</li>
      <li class='no-friends-notice'>Add friends by clicking here <i class='icon-reply icon-rotate'></i></li>
      <li class='share-to-fb-notice'><i class='icon-facebook-sign'></i> Drag to Share</li>
    <% } %>
  </ul>
  """

  @notLoggedInTemplate:"""
    <ul class='friends no-friends not-logged-in'>
      <li class='not-logged-in-notice'>Log In to share</li>
    </ul>
  """


  @expandButtonTemplate: """
  <a href='#' onclick='A2Cribs.HotlistObj.toggleExpand()' id='expand-button'><i class='icon icon-caret-down'></i></a>
  """
