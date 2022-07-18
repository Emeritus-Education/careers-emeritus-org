window.addEventListener('leverJobsRendered', function() {
  
     $(".lever-job").clone().appendTo("#new-list ul");

     var options = {
       valueNames: [
         'lever-job-title',
         { data: ['location'] },
         { data: ['department'] },
         { data: ['team'] },
         { data: ['work-type'] }
       ]
     };

      var jobList = new List('new-list', options);

      console.log("joblist", jobList);

      var locations = [];
      var departments = [];
      var teams = [];
      var workTypes = [];
      for (var i = 0; i < jobList.items.length; i++) {
        var item = jobList.items[i]._values;
        var location = item.location;
        if(jQuery.inArray(location, locations) == -1) {
          locations.push(location);
        }
        var department = item.department;
        if(jQuery.inArray(department, departments) == -1) {
          departments.push(department);
        }
        var team = item.team;
        if(jQuery.inArray(team, teams) == -1) {
          teams.push(team);
        }
        var workType = item["work-type"];
        if(jQuery.inArray(workType, workTypes) == -1) {
          workTypes.push(workType);
        }
      }

    locations.sort();
    departments.sort();
    teams.sort();
    workTypes.sort();
    for (var j = 0; j < locations.length; j++ ) {
        $( "#lever-jobs-filter .lever-jobs-filter-locations").append('<option class="drop-list">' + locations[j] + '</option>');
    }
    for (var j = 0; j < departments.length; j++ ) {
        $( "#lever-jobs-filter .lever-jobs-filter-departments").append('<option class=department drop-list>' + departments[j] + '</option>');
    }
    for (var j = 0; j < teams.length; j++ ) {
        $( "#lever-jobs-filter .lever-jobs-filter-teams").append('<option class="drop-list">' + teams[j] + '</option>');
    }
    for (var j = 0; j < workTypes.length; j++ ) {
        $( "#lever-jobs-filter .lever-jobs-filter-work-types").append('<option class="drop-list">' + workTypes[j] + '</option>');
    }

    function showFilterResults() {
      $('#new-list .list').show();
      $('#lever-jobs-container').hide();
    }
    function hideFilterResults() {
      $('#new-list .list').hide();
      $('#lever-jobs-container').show();
    }

    // Show the unfiltered list by default
    hideFilterResults();

   $('#lever-jobs-filter select').change(function(){

    var selectedFilters = {
      location: $('#lever-jobs-filter select.lever-jobs-filter-locations').val(),
      department: $('#lever-jobs-filter select.lever-jobs-filter-departments').val(),
      team: $('#lever-jobs-filter select.lever-jobs-filter-teams').val(),
      'work-type': $('#lever-jobs-filter select.lever-jobs-filter-work-types').val(),
    }

    //Filter the list
    jobList.filter(function(item) {
      var itemValue = item.values();
      // Check the itemValue against all filter properties (location, team, work-type).
      for (var filterProperty in selectedFilters) {
        var selectedFilterValue = selectedFilters[filterProperty];

        // For a <select> that has no option selected, JQuery's val() will return null.
        // We only want to compare properties where the user has selected a filter option,
        // which is when selectedFilterValue is not null.
        if (selectedFilterValue !== null) {
          if (itemValue[filterProperty] !== selectedFilterValue) {
            // Found mismatch with a selected filter, can immediately exclude this item.
            return false;
          }
        }
      }
      // This item passes all selected filters, include this item.
      return true;
    });

    //Show the 'no results' message if there are no matching results
    if (jobList.visibleItems.length >= 1) {
      $('#lever-no-results').hide();
    }
    else {
      $('#lever-no-results').show();
    }

    console.log("filtered?", jobList.filtered);


    $('#lever-clear-filters').show();

    //Show the list with filtered results
    showFilterResults();

  });


  $('#new-list').on('click', '#lever-clear-filters', function() {
    $(".lever-jobs-filter-locations").val(null).trigger('change');
    $(".lever-jobs-filter-departments").val(null).trigger('change');
    $(".lever-jobs-filter-teams").val(null).trigger('change');
    $(".lever-jobs-filter-work-types").val(null).trigger('change');
    console.log("clicked clear filters");
    jobList.filter();
    console.log("jobList filtered?", jobList.filtered);
    if (jobList.filtered == false) {
      hideFilterResults();
    }
    $('#lever-jobs-filter select').prop('selectedIndex',0);
    $('#lever-clear-filters').hide();
    $('#lever-no-results').hide();
  });

   // Showing/hiding search results when the search box is empty
   $('#new-list').on('input', '#lever-jobs-search', function() {
      if($(this).val().length || jobList.filtered == true) {
        showFilterResults();
        if (jobList.visibleItems.length >= 1) {
          $('#lever-no-results').hide();
        } else {
          $('#lever-no-results').show();
        }
      } else {
        hideFilterResults();
        $('#lever-no-results').hide();
      }
    });

})