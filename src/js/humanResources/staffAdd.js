require.config({
    baseUrl: '../src/js'
});
require(['commonConfig'], function () {
  require(['service', 'common', 'jsrender'], function(service,common) {
    var
      maintainPrivilage = common.checkPrivilage('STAFF_ADD'),
      provinces = [],
      orgLevel1 = [],
      orgLevel2 = [],
      orgLevel3 = [],
      grades = [],
      $province =  $('#province'),
      $city = $('#city');
      $orgLevel1 =  $('#orgLevel1'),
      $orgLevel2 =  $('#orgLevel2'),
      $orgLevel3 =  $('#orgLevel3'),
      $grade =  $('#grade'),
      $position =  $('#position'),
      $addCareer = $('#addCareer'),
      $addTraining = $('#addTraining'),
      $expTbody = $('.exp-tbody'),
      $btnSave = $('.btn-save');
    initPage();

    $province.on('change', function (e) {
      fillCity();
    });

    $orgLevel1.on('change', function (e) {
      clearOrgLevel2();
      clearOrgLevel3();
      fillOrgLevel2();
    });

    $orgLevel2.on('change', function (e) {
      clearOrgLevel3();
      fillOrgLevel3();
    });

    $grade.on('change', function (e) {
      fillPosition();
    });

    $addCareer.on('click', function (e) {
      addExpRow('career');
    });

    $addTraining.on('click', function (e) {
      addExpRow('training');
    });

    $expTbody.on('click', function (e) {
      $target = $(e.target);
      if ($target.hasClass('delete-row')) {
        $target.closest('tr').remove();
      }
    });

    $btnSave.on('click', function (e) {
      saveStaff();
    });

    function initPage () {
      common.initGolbal();
      $('#enterDate').val(common.getDateString());
      $('#headManpowerLi').addClass('active');
      initProvinceCity();
      init3Level();
      initPosition();
      addExpRow('career');
      addExpRow('training');
    }

    function saveStaff () {
      $.ajax({
          url: service.SAVE_STAFF,
          type: 'post',
          dataType: 'json',
          data: {
            'staffData': packStaffData(),
            'expData': packExpData()
          },
          error: function () {
            common.alertError();
          },
          success: function (response) {
            if(response.success) {
              clearStaffData();
            } else {
              bootbox.alert(response.message);
            }
          }
      });
    }

    function clearStaffData() {

    }

    function packStaffData() {
      var packData = {};

      packData.name = $('#staffName').val();
      packData.contact_phone = $('#phone').val();
      packData.gender = $('input:radio[name=gender]:checked').val();
      packData.id_number = $('#idNumber').val();
      packData.native_city = $('#city').val();
      packData.education = $('#education').val();
      packData.major = $('#major').val();
      packData.school = $('#school').val();
      packData.emergency_contact = $('#emergencyContact').val();
      packData.emergency_phone = $('#emergencyPhone').val();
      packData.employee_number = $('#employeeNumber').val();
      packData.dept_id = $('#orgLevel3').val() || $('#orgLevel2').val() || $('#orgLevel1').val();
      packData.position_id = $('#position').val();
      packData.basic_salary = $('#basicSalary').val();
      packData.enter_date = $('#enterDate').val();
      packData.start_date = $('#startDate').val();
      packData.email = $('#email').val();

      var retData = JSON.stringify(packData);
      return retData;
    }

    function packExpData() {
      var
        expArr = ['career', 'training'],
        expData = [];
      $.each(expArr, function (index, type) {
        var
          $trs = $('#' + type).find('tr');
        $.each($trs, function (index, tr) {
          $tr = $(tr);
          var expObj = {};
          expObj.type = type;
          expObj.start_date = $tr.find('.start-date').val();
          expObj.end_date = $tr.find('.end-date').val();
          expObj.description = $tr.find('.desc').val();

          expData.push(expObj);
        });
      });
      var retData = JSON.stringify(expData);
      return retData;
    }

    function initProvinceCity () {
      $.ajax({
        url: service.GET_PROVINCE_CITY_LIST,
        type: 'post',
        dataType: 'json',
        data: {},
        error: function () {
          common.alertError();
        },
        success: function (response) {
          if(response.success) {
            provinces = response.data;
            var $select = $('<select />');
            $.each(provinces, function (index, province) {
              $('<option />').val(index).html(province.name).appendTo($select);
            });
            $province.append($select.html());
          } else {
            bootbox.alert(response.message);
          }
        }
      });
    }

    function fillCity () {
      var
        cities = provinces[$province.val()] && provinces[$province.val()].cities,
        $select = $('<select />');
      $select.append('<option value="0" disabled>请选择城市</option>');
      if (cities) {
        $.each(cities, function (index, city) {
          $('<option />').val(city.id).html(city.name).appendTo($select);
        });
      }
      $city.html($select.html());
    }

    function init3Level () {
      $.ajax({
        url: service.GET_3_LEVEL_LIST,
        type: 'post',
        dataType: 'json',
        data: {},
        error: function () {
          common.alertError();
        },
        success: function (response) {
          if(response.success) {
            orgLevel1 = response.data.level1;
            orgLevel2 = response.data.level2;
            orgLevel3 = response.data.level3;

            var $select = $('<select />');
            $.each(orgLevel1, function (index, org) {
              $('<option />').val(org.id).html(org.display_name).appendTo($select);
            });
            $orgLevel1.append($select.html());
          } else {
            bootbox.alert(response.message);
          }
        }
      });
    }

    function clearOrgLevel2() {
      $orgLevel2.html('<option value="">- 班 -</option>');
    }

    function clearOrgLevel3() {
      $orgLevel3.html('<option value="">- 组 -</option>');
    }

    function fillOrgLevel2() {
      var
        level2 = orgLevel2[$orgLevel1.val()],
        $select = $('<select />');
      $select.append('<option value="">- 班 -</option>');
      if (level2) {
        $.each(level2, function (index, org) {
          $('<option />').val(org.id).html(org.display_name).appendTo($select);
        });
      }
      $orgLevel2.html($select.html());
    }

    function fillOrgLevel3() {
      var
        level3 = orgLevel3[$orgLevel2.val()],
        $select = $('<select />');
      $select.append('<option value="">- 组 -</option>');
      if (level3) {
        $.each(level3, function (index, org) {
          $('<option />').val(org.id).html(org.display_name).appendTo($select);
        });
      }
      $orgLevel3.html($select.html());
    }

    function initPosition() {
      $.ajax({
        url: service.GET_GRADE_POSITION_LIST,
        type: 'post',
        dataType: 'json',
        data: {},
        error: function () {
          common.alertError();
        },
        success: function (response) {
          if(response.success) {
            grades = response.data
            var $select = $('<select />');
            $.each(grades, function (gradeName, positions) {
              $('<option />').val(gradeName).html(gradeName).appendTo($select);
            });
            $grade.append($select.html());
          } else {
            bootbox.alert(response.message);
          }
        }
      });
    }

    function fillPosition() {
      var
        positions = grades[$grade.val()],
        $select = $('<select />');
      $select.append('<option value="" disabled>岗位名称</option>');
      if (positions) {
        $.each(positions, function (index, position) {
          $('<option />').val(position.id).html(position.display_name).appendTo($select);
        });
      }
      $position.html($select.html());
    }

    function addExpRow(type) {
      var $tbody = $('#'+type);
      var $tr = $('<tr />');
      $input = $('<input />')
          .addClass('form-control')
          .attr('type', 'text')
          .on('click', function () {
            WdatePicker({dateFmt:'yyyy-MM-dd'})
          });
      $deleteTd = $('<td />').append('<button type="button" class="close"><span class="delete-row">×</span></button>');
      $startTd = $('<td />').append($input.clone(true).attr('placeholder', '请输入开始日期').addClass('start-date'));
      $endTd = $('<td />').append($input.clone(true).attr('placeholder', '请输入结束日期').addClass('end-date'));
      $descTd = $('<td />').append($('<textarea />').addClass('form-control desc').attr('rows', '1'));
      $tr.append($deleteTd).append($startTd).append($endTd).append($descTd).appendTo($tbody);
    }
  });
});
