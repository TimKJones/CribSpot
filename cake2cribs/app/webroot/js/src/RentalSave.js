(function() {

  A2Cribs.RentalSave = (function() {

    function RentalSave(modal) {
      modal = $('.rental-content');
      this.EditableRows = [];
      this.VisibleGrid = 'overview_grid';
      this.SetupUI();
      this.NextListing;
    }

    RentalSave.prototype.SetupUI = function() {
      /*
      		********************* TODO **********************
      */
      var _this = this;
      if (!(A2Cribs.Geocoder != null)) {
        A2Cribs.Geocoder = new google.maps.Geocoder();
      }
      $('body').on("Rental_SavePhoto", function(event, row, images, listing_id) {
        var data, image, _i, _len, _results;
        if (listing_id != null) {
          _results = [];
          for (_i = 0, _len = images.length; _i < _len; _i++) {
            image = images[_i];
            _results.push(A2Cribs.UserCache.Set(new Image(image)));
          }
          return _results;
        } else {
          data = _this.GridMap[_this.VisibleGrid].getDataItem(row);
          return data.Image = images;
        }
      });
      $("body").on('click', '.rentals_list_item', function(event) {
        return _this.Open(event.target.id);
      });
      $("#rentals_edit").click(function(event) {
        var data, row, selected, _i, _j, _len, _len2, _ref;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        if (_this.EditableRows.length) {
          _this.GridMap[_this.VisibleGrid].getEditorLock().commitCurrentEdit();
          $(event.target).text("Edit");
          $(".rentals_tab").removeClass("highlight-tab");
          _ref = _this.EditableRows;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            row = _ref[_i];
            data = _this.GridMap[_this.VisibleGrid].getDataItem(row);
            data.editable = false;
          }
          _this.GridMap[_this.VisibleGrid].setSelectedRows(_this.EditableRows);
          _this.EditableRows = [];
        } else if (selected.length) {
          _this.EditableRows = selected;
          $(event.target).text("Finish Editing");
          for (_j = 0, _len2 = selected.length; _j < _len2; _j++) {
            row = selected[_j];
            data = _this.GridMap[_this.VisibleGrid].getDataItem(row);
            data.editable = true;
          }
        }
        return _this.GridMap[_this.VisibleGrid].setSelectedRows(selected);
      });
      $("#rentals_delete").click(function() {
        var listings, row, selected, _i, _len;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        if (selected.length) {
          listings = [];
          for (_i = 0, _len = selected.length; _i < _len; _i++) {
            row = selected[_i];
            if (_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id != null) {
              listings.push(_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id);
            }
          }
          return _this.Delete(selected, listings);
        }
      });
      $(".rentals_tab").click(function(event) {
        var selected;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        _this.VisibleGrid = $(event.target).attr("href").substring(1);
        _this.GridMap[_this.VisibleGrid].setSelectedRows(selected);
        return $(event.target).removeClass("highlight-tab");
      });
      $(".rentals-content").on("shown", function(event) {
        var grid, width, _results;
        width = $("#" + _this.VisibleGrid).width();
        _results = [];
        for (grid in _this.GridMap) {
          $("#" + grid).css("width", "" + width + "px");
          _results.push(_this.GridMap[grid].init());
        }
        return _results;
      });
      this.CreateGrids();
      return this.MarkerModalSetup();
    };

    RentalSave.prototype.Open = function(marker_id) {
      var marker_object, name;
      this.ClearGrids();
      this.CurrentMarker = marker_id;
      marker_object = A2Cribs.UserCache.Get("marker", this.CurrentMarker);
      name = (marker_object.alternate_name != null) && marker_object.alternate_name.length ? marker_object.alternate_name : marker_object.street_address;
      $("#rentals_address").html("<strong>" + name + "</strong><br>");
      A2Cribs.Dashboard.ShowContent($(".rentals-content"), true);
      return this.PopulateGrid(marker_id);
    };

    RentalSave.prototype.Save = function(row, rental_object) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "listings/Save/",
        type: "POST",
        data: rental_object,
        success: function(response) {
          var i, key, value, _i, _len;
          response = JSON.parse(response);
          if (response.listing_id != null) {
            A2Cribs.UIManager.Success("Save successful!");
            rental_object.Listing.listing_id = response.listing_id;
            rental_object.Rental.listing_id = response.listing_id;
            for (key in rental_object) {
              value = rental_object[key];
              if ((A2Cribs[key] != null) && !(value.length != null)) {
                A2Cribs.UserCache.Set(new A2Cribs[key](value));
              } else if ((A2Cribs[key] != null) && (value.length != null)) {
                for (_i = 0, _len = value.length; _i < _len; _i++) {
                  i = value[_i];
                  i.listing_id = response.listing_id;
                  A2Cribs.UserCache.Set(new A2Cribs[key](i));
                }
              }
            }
            return console.log(response);
          } else {
            A2Cribs.UIManager.Error("Save unsuccessful");
            return console.log(response);
          }
        }
      });
    };

    /*
    	Test function for Listings/GetListing.
    	Retrieves the listing specified by listing_id.
    	If listing_id is null, retrieves all listings owned by the logged-in user.
    */

    RentalSave.prototype.GetListing = function(listing_id) {
      var url,
        _this = this;
      if (listing_id == null) listing_id = null;
      url = myBaseUrl + 'listings/GetListing/';
      if (listing_id !== null) url = url + listing_id;
      return $.ajax({
        url: url,
        type: "POST",
        success: function(response) {
          return console.log(JSON.parse(response));
        }
      });
    };

    RentalSave.prototype.Copy = function(rental_ids) {
      /*
      		********************* TODO (Not first priority) *
      */
    };

    RentalSave.prototype.Delete = function(rows, listing_ids) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "listings/Delete/" + JSON.stringify(listing_ids),
        type: "POST",
        success: function(response) {
          var data, listing_id, rental, rentals, row, _i, _j, _k, _len, _len2, _len3;
          response = JSON.parse(response);
          if (response.success !== null && response.success !== void 0) {
            A2Cribs.UIManager.Success("Listings deleted!");
            data = _this.GridMap[_this.VisibleGrid].getData();
            for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
              listing_id = listing_ids[_i];
              rentals = A2Cribs.UserCache.GetAllAssociatedObjects("rental", "listing", listing_id);
              for (_j = 0, _len2 = rentals.length; _j < _len2; _j++) {
                rental = rentals[_j];
                A2Cribs.UserCache.Remove(rental.class_name, rental.GetId());
              }
              A2Cribs.UserCache.Remove("listing", listing_id);
            }
            for (_k = 0, _len3 = rows.length; _k < _len3; _k++) {
              row = rows[_k];
              data.splice(row, 1);
            }
            _this.GridMap[_this.VisibleGrid].updateRowCount();
            return _this.GridMap[_this.VisibleGrid].render();
          } else {
            A2Cribs.UIManager.Error("Delete unsuccessful");
            return console.log(response);
          }
        }
      });
    };

    RentalSave.prototype.Create = function(marker_id) {
      /*
      		********************* TODO **********************
      */
      var data, grid, key, _ref;
      this.CurrentMarker = marker_id;
      A2Cribs.Dashboard.ShowContent($(".rentals-content"), true);
      _ref = this.GridMap;
      for (key in _ref) {
        grid = _ref[key];
        grid.init();
      }
      return data = this.GridMap["overview_grid"].getData();
    };

    /*
    	Called when user adds a new row for the existing marker
    	Adds a new row to the grid, with a new row_id.
    	Sets the row_id hidden field.
    */

    RentalSave.prototype.AddNewUnit = function() {
      var container, data, grid, row, row_number, _i, _len, _ref, _ref2, _results;
      this.GridMap[this.VisibleGrid].getEditorLock().commitCurrentEdit();
      data = this.GridMap[this.VisibleGrid].getData();
      _ref = this.EditableRows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        data[row].editable = false;
      }
      row_number = data.length;
      this.EditableRows = [row_number];
      data.push({
        editable: true
      });
      this.GridMap[this.VisibleGrid].setSelectedRows(this.EditableRows);
      $("#rentals_edit").text("Finish Editing");
      $('a[href="#overview_grid"]').addClass("highlight-tab");
      $('a[href="#description_grid"]').addClass("highlight-tab");
      $('a[href="#contact_grid"]').addClass("highlight-tab");
      $('a[href="#' + this.VisibleGrid + '"]').removeClass("highlight-tab");
      _ref2 = this.GridMap;
      _results = [];
      for (container in _ref2) {
        grid = _ref2[container];
        grid.updateRowCount();
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.MarkerModalSetup = function() {
      var clear, marker_validate, modal,
        _this = this;
      modal = $('#marker-modal');
      clear = function() {
        modal.find("input").val("");
        modal.find('select option:first-child').attr("selected", "selected");
        return _this.MiniMap.SetMarkerVisible(false);
      };
      modal.on('show', function() {
        var marker, markers, name, option, _i, _len;
        clear();
        modal.find('#marker_add').hide();
        modal.find("#continue-button").addClass("disabled");
        markers = A2Cribs.UserCache.Get("marker");
        modal.find("#marker_select").empty();
        modal.find("#marker_select").append('<option value="0">--</option>\
				<option value="new_marker"><strong>New Location</strong></option>');
        if (markers != null) {
          for (_i = 0, _len = markers.length; _i < _len; _i++) {
            marker = markers[_i];
            name = (marker.alternate_name != null) && marker.alternate_name.length ? marker.alternate_name : marker.street_address;
            option = $("<option />", {
              text: name,
              value: marker.marker_id
            });
            modal.find("#marker_select").append(option);
          }
        }
        return modal.find("#marker_select").val("0");
      });
      modal.on('shown', function() {
        return _this.MiniMap.Resize();
      });
      modal.find(".required").keydown(function() {
        return $(this).parent().removeClass("error");
      });
      modal.find("#University_name").focusout(function() {
        _this.FindSelectedUniversity(modal);
        if (_this.SelectedUniversity != null) {
          return _this.MiniMap.CenterMap(_this.SelectedUniversity.latitude, _this.SelectedUniversity.longitude);
        }
      });
      modal.find("#place_map_button").click(function() {
        return _this.FindAddress(modal);
      });
      modal.find("#marker_select").change(function() {
        var marker_selected;
        marker_selected = modal.find("#marker_select").val();
        if (marker_selected === "0") {
          modal.find("#continue-button").addClass("disabled");
        } else {
          modal.find("#continue-button").removeClass("disabled");
        }
        if (marker_selected === "new_marker") {
          modal.find('#marker_add').show();
          return _this.MiniMap.Resize();
        } else {
          return modal.find('#marker_add').hide();
        }
      });
      marker_validate = function() {
        var isValid;
        isValid = true;
        if (!modal.find('#Marker_street_address').val()) {
          A2Cribs.UIManager.Error("Please place your street address on the map using the Place On Map button.");
          modal.find('#Marker_street_address').parent().addClass("error");
          isValid = false;
        }
        if (!modal.find('#University_name').val()) {
          A2Cribs.UIManager.Error("You need to select a university.");
          modal.find('#University_name').parent().addClass("error");
          isValid = false;
        }
        if (modal.find('#Marker_building_type_id').val().length === 0) {
          A2Cribs.UIManager.Error("You need to select a building type.");
          modal.find('#Marker_building_type_id').parent().addClass("error");
          isValid = false;
        }
        if (modal.find('#Marker_alternate_name').val().length >= 249) {
          A2Cribs.UIManager.Error("Your alternate name is too long.");
          modal.find('#Marker_alternate_name').parent().addClass("error");
          isValid = false;
        }
        return isValid;
      };
      modal.find("#continue-button").click(function() {
        var marker_object, marker_selected;
        marker_selected = modal.find("#marker_select").val();
        if (marker_selected === "new_marker") {
          if (marker_validate()) {
            marker_object = {
              alternate_name: modal.find('#Marker_alternate_name').val(),
              building_type_id: modal.find('#Marker_building_type_id').val(),
              street_address: modal.find('#Marker_street_address').val(),
              city: modal.find('#Marker_city').val(),
              state: modal.find('#Marker_state').val(),
              zip: modal.find('#Marker_zip').val(),
              latitude: modal.find('#Marker_latitude').val(),
              longitude: modal.find('#Marker_longitude').val()
            };
            return $.ajax({
              url: "/Markers/Save/",
              type: "POST",
              data: marker_object,
              success: function(response) {
                var list_item, name;
                if (response.error) {
                  return UIManager.Error(response.error);
                } else {
                  modal.modal("hide");
                  marker_object.marker_id = response;
                  A2Cribs.UserCache.Set(new A2Cribs.Marker(marker_object));
                  name = (marker_object.alternate_name != null) && marker_object.alternate_name.length ? marker_object.alternate_name : marker_object.street_address;
                  list_item = $("<li />", {
                    text: name,
                    "class": "rentals_list_item",
                    id: marker_object.marker_id
                  });
                  $("#rentals_list").append(list_item);
                  $("#rentals_list").slideDown();
                  _this.Open(response);
                  return _this.AddNewUnit();
                }
              }
            });
          }
        } else if (marker_selected !== "0") {
          modal.modal("hide");
          _this.Open(marker_selected);
          return _this.AddNewUnit();
        }
      });
      this.MiniMap = new A2Cribs.MiniMap(modal);
      if (A2Cribs.Cache.SchoolList != null) {
        modal.find("#University_name").typeahead({
          source: A2Cribs.Cache.SchoolList
        });
        return;
      }
      return $.ajax({
        url: "/University/getAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.Cache.universitiesMap = JSON.parse(response);
          A2Cribs.Cache.SchoolList = [];
          A2Cribs.Cache.SchoolIDList = [];
          _ref = A2Cribs.Cache.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.Cache.SchoolList.push(university.University.name);
            A2Cribs.Cache.SchoolIDList.push(university.University.id);
          }
          return modal.find("#University_name").typeahead({
            source: A2Cribs.Cache.SchoolList
          });
        }
      });
    };

    RentalSave.prototype.FindSelectedUniversity = function(div) {
      var index, selected;
      selected = div.find("#University_name").val();
      index = A2Cribs.Cache.SchoolList.indexOf(selected);
      if (index >= 0) {
        return this.SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
      } else {
        return this.SelectedUniversity = null;
      }
    };

    RentalSave.prototype.FindAddress = function(div) {
      var address, addressObj,
        _this = this;
      if (this.SelectedUniversity != null) {
        address = div.find("#Marker_street_address").val();
        addressObj = {
          'address': address + " " + this.SelectedUniversity.city + ", " + this.SelectedUniversity.state
        };
        return A2Cribs.Geocoder.geocode(addressObj, function(response, status) {
          var component, street_name, street_number, type, _i, _j, _len, _len2, _ref, _ref2;
          if (status === google.maps.GeocoderStatus.OK && response[0].address_components.length >= 2) {
            _ref = response[0].address_components;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              component = _ref[_i];
              _ref2 = component.types;
              for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
                type = _ref2[_j];
                switch (type) {
                  case "street_number":
                    street_number = component.short_name;
                    break;
                  case "route":
                    street_name = component.short_name;
                    break;
                  case "locality":
                    div.find('#Marker_city').val(component.short_name);
                    break;
                  case "administrative_area_level_1":
                    div.find('#Marker_state').val(component.short_name);
                    break;
                  case "postal_code":
                    div.find('#Marker_zip').val(component.short_name);
                }
              }
            }
            if (!(street_number != null)) {
              A2Cribs.UIManager.Alert("Entered street address is not valid.");
              $("#Marker_street_address").text("");
              return;
            }
            _this.MiniMap.SetMarkerPosition(response[0].geometry.location);
            div.find("#Marker_street_address").val(street_number + " " + street_name);
            div.find("#Marker_latitude").val(response[0].geometry.location.lat());
            return div.find("#Marker_longitude").val(response[0].geometry.location.lng());
          }
        });
      }
    };

    RentalSave.prototype.PopulateGrid = function(marker_id) {
      /*
      		********************* TODO **********************
      */
      var data, grid, key, listing, rental, rentals, _i, _len, _ref, _results;
      rentals = A2Cribs.UserCache.Get("rental");
      data = [];
      if (rentals.length) {
        for (_i = 0, _len = rentals.length; _i < _len; _i++) {
          rental = rentals[_i];
          listing = A2Cribs.UserCache.Get("listing", rental.listing_id);
          if (listing.marker_id === this.CurrentMarker) {
            data.push(rental.GetObject());
          }
        }
      }
      _ref = this.GridMap;
      _results = [];
      for (key in _ref) {
        grid = _ref[key];
        grid.setData(data);
        grid.updateRowCount();
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.ClearGrids = function() {
      var container, data, grid, _ref, _results;
      _ref = this.GridMap;
      _results = [];
      for (container in _ref) {
        grid = _ref[container];
        data = [];
        grid.setData(data);
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.CreateGrids = function() {
      var checkboxSelector, columnpicker, columns, container, containers, data, options, _i, _len, _results,
        _this = this;
      containers = ["overview_grid", "features_grid", "amenities_grid", "utilities_grid", "buildingamenities_grid", "fees_grid", "description_grid", "picture_grid", "contact_grid"];
      this.GridMap = {};
      options = {
        editable: true,
        enableCellNavigation: true,
        asyncEditorLoading: false,
        enableAddRow: false,
        autoEdit: true,
        forceFitColumns: true,
        explicitInitialization: true,
        rowHeight: 35
      };
      data = [];
      _results = [];
      for (_i = 0, _len = containers.length; _i < _len; _i++) {
        container = containers[_i];
        columns = this.GetColumns(container);
        checkboxSelector = new Slick.CheckboxSelectColumn({
          cssClass: "grid_checkbox"
        });
        columns[0] = checkboxSelector.getColumnDefinition();
        this.GridMap[container] = new Slick.Grid("#" + container, data, columns, options);
        this.GridMap[container].setSelectionModel(new Slick.RowSelectionModel({
          selectActiveRow: false
        }));
        this.GridMap[container].registerPlugin(checkboxSelector);
        columnpicker = new Slick.Controls.ColumnPicker(columns, this.GridMap[container], options);
        this.GridMap[container].onBeforeEditCell.subscribe(function(e, args) {
          if (_this.EditableRows.indexOf(args.row) !== -1) {
            console.log("lol");
            return true;
          } else {
            return false;
          }
        });
        _results.push(this.GridMap[container].onCellChange.subscribe(function(e, args) {
          var isValid, key, required, _j, _len2;
          columns = _this.GridMap[container].getColumns();
          required = A2Cribs.Rental.Required_Fields;
          data = {
            Rental: {},
            Listing: {},
            Image: {}
          };
          isValid = true;
          for (_j = 0, _len2 = required.length; _j < _len2; _j++) {
            key = required[_j];
            isValid = isValid && (args.item[key] != null);
          }
          if (isValid) {
            data.Rental = args.item;
            if (!(data.Rental.listing_id != null)) {
              data.Listing.listing_type = 0;
              data.Listing.marker_id = _this.CurrentMarker;
              if (data.Rental.Image != null) data.Image = data.Rental.Image;
            } else {
              data.Listing = A2Cribs.UserCache.Get("listing", data.Rental.listing_id);
              data.Image = A2Cribs.UserCache.GetAllAssociatedObjects("image", "listing", data.Rental.listing_id);
            }
            return _this.Save(args.row, data);
          }
        }));
      }
      return _results;
    };

    RentalSave.prototype.GetColumns = function(container) {
      var AmenitiesColumns, BuildingAmenitiesColumns, ContactColumns, DescriptionColumns, FeaturesColumns, FeesColumns, OverviewColumns, PictureColumns, UtilitiesColumns;
      OverviewColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "beds",
            name: "Beds",
            field: "beds",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "occupancy",
            name: "Occupancy",
            field: "occupancy",
            formatter: A2Cribs.Formatters.Range,
            editor: A2Cribs.Editors.Range
          }, {
            id: "rent",
            name: "Total Rent",
            field: "rent",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.RequiredMoney
          }, {
            id: "rent_negotiable",
            cssClass: "grid_checkbox",
            name: "(Neg.)",
            field: "rent_negotiable",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "start_date",
            name: "Start Date",
            field: "start_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "alternate_start_date",
            name: "Alt. Start Date",
            field: "alternate_start_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "end_date",
            name: "End Date",
            field: "end_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "available",
            name: "Availability",
            field: "available",
            editor: A2Cribs.Editors.Availability,
            formatter: A2Cribs.Formatters.Availability
          }, {
            id: "unit_count",
            name: "Unit Count",
            field: "unit_count",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.RequiredText
          }
        ];
      };
      FeaturesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "baths",
            name: "Baths",
            field: "baths",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "parking_type",
            name: "Parking",
            field: "parking_type",
            editor: A2Cribs.Editors.Parking,
            formatter: A2Cribs.Formatters.Parking
          }, {
            id: "parking_spots",
            name: "Spots",
            field: "parking_spots",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "street_parking",
            cssClass: "grid_checkbox",
            name: "Street Parking",
            field: "street_parking",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "furnished_type",
            name: "Furnished",
            field: "furnished_type",
            editor: A2Cribs.Editors.Furnished,
            formatter: A2Cribs.Formatters.Furnished
          }, {
            id: "pets_type",
            name: "Pets",
            field: "pets_type",
            editor: A2Cribs.Editors.Pets,
            formatter: A2Cribs.Formatters.Pets
          }, {
            id: "smoking",
            name: "Smoking",
            field: "smoking",
            editor: A2Cribs.Editors.Smoking,
            formatter: A2Cribs.Formatters.Smoking
          }, {
            id: "square_feet",
            name: "SQ Feet",
            field: "square_feet",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "year_built",
            name: "Year Built",
            field: "year_built",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      AmenitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "air",
            cssClass: "grid_checkbox",
            name: "A/C",
            field: "air",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "washer",
            name: "Washer/Dryer",
            field: "washer"
          }, {
            id: "fridge",
            cssClass: "grid_checkbox",
            name: "Fridge",
            field: "fridge",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "balcony",
            cssClass: "grid_checkbox",
            name: "Balcony",
            field: "balcony",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "tv",
            cssClass: "grid_checkbox",
            name: "TV",
            field: "tv",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "storage",
            cssClass: "grid_checkbox",
            name: "Storage",
            field: "storage",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "security_system",
            cssClass: "grid_checkbox",
            name: "Security System",
            field: "security_system",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }
        ];
      };
      BuildingAmenitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "pool",
            cssClass: "grid_checkbox",
            name: "Pool",
            field: "pool",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "hot_tub",
            cssClass: "grid_checkbox",
            name: "Hot Tubs",
            field: "hot_tub",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "fitness_center",
            cssClass: "grid_checkbox",
            name: "Fitness Center",
            field: "fitness_center",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "game_room",
            cssClass: "grid_checkbox",
            name: "Game Room",
            field: "game_room",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "front_desk",
            cssClass: "grid_checkbox",
            name: "Front Desk",
            field: "front_desk",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "tanning_beds",
            cssClass: "grid_checkbox",
            name: "Tanning Beds",
            field: "tanning_beds",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "study_lounge",
            cssClass: "grid_checkbox",
            name: "Study Lounge",
            field: "study_lounge",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "patio_deck",
            cssClass: "grid_checkbox",
            name: "Deck/Patio",
            field: "patio_deck",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "yard_space",
            cssClass: "grid_checkbox",
            name: "Yard Space",
            field: "yard_space",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "elevator",
            cssClass: "grid_checkbox",
            name: "Elevator",
            field: "elevator",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }
        ];
      };
      UtilitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "electric",
            name: "Electricity",
            field: "electric",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "water",
            name: "Water",
            field: "water",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "gas",
            name: "Gas",
            field: "gas",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "heat",
            name: "Heat",
            field: "heat",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "sewage",
            name: "Sewage",
            field: "sewage",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "trash",
            name: "Trash",
            field: "trash",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "cable",
            name: "Cable",
            field: "cable",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "internet",
            name: "Internet",
            field: "internet",
            editor: A2Cribs.Editors.Utilities,
            formatter: A2Cribs.Formatters.Utilities
          }, {
            id: "utility_total_flat_rate",
            name: "Total Flat Rate",
            field: "utility_total_flat_rate",
            editor: A2Cribs.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "utility_estimate_winter",
            name: "Est. Winter Utility Cost",
            field: "utility_estimate_winter",
            editor: A2Cribs.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "utility_estimate_summer",
            name: "Est. Summer Utility Cost",
            field: "utility_estimate_summer",
            editor: A2Cribs.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }
        ];
      };
      FeesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "deposit_amount",
            name: "Deposit",
            field: "deposit_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "admin_amount",
            name: "Admin",
            field: "admin_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "parking_amount",
            name: "Parking",
            field: "parking_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "furniture_amount",
            name: "Furniture",
            field: "furniture_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "pets_amount",
            name: "Pets",
            field: "pets_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "amenity_amount",
            name: "Amenity",
            field: "amenity_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "upper_floor_amount",
            name: "Upper Floor",
            field: "upper_floor_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "extra_occupant_amount",
            name: "Cost for Extra Occupant",
            field: "extra_occupant_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }
        ];
      };
      DescriptionColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "highlights",
            name: "Highlights",
            field: "highlights",
            editor: Slick.Editors.LongText,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "description",
            name: "Description",
            field: "description",
            editor: Slick.Editors.LongText,
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      PictureColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "pictures",
            name: "Pictures",
            formatter: A2Cribs.Formatters.Button
          }
        ];
      };
      ContactColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "waitlist",
            name: "Waitlist",
            field: "waitlist",
            editor: Slick.Editors.YesNoSelect,
            formatter: Slick.Formatters.YesNo
          }, {
            id: "waitlist_open_date",
            name: "waitlist_open_date",
            field: "waitlist_open_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "lease_office_address",
            name: "lease_office_address",
            field: "lease_office_address",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "contact_email",
            name: "contact_email",
            field: "contact_email",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "contact_phone",
            name: "contact_phone",
            field: "contact_phone",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "website",
            name: "website",
            field: "website",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.RequiredText
          }
        ];
      };
      switch (container) {
        case "overview_grid":
          return OverviewColumns();
        case "features_grid":
          return FeaturesColumns();
        case "amenities_grid":
          return AmenitiesColumns();
        case "utilities_grid":
          return UtilitiesColumns();
        case "fees_grid":
          return FeesColumns();
        case "description_grid":
          return DescriptionColumns();
        case "picture_grid":
          return PictureColumns();
        case "contact_grid":
          return ContactColumns();
        case "buildingamenities_grid":
          return BuildingAmenitiesColumns();
      }
    };

    return RentalSave;

  })();

}).call(this);
