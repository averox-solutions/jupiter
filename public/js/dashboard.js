(function () {
    'use strict';

    //update meeting details when a meeting is clicked
    $(document).on("click", ".meeting-card", function () {
        let meetingTitle = $(this).find(".meeting-title").text(),
            meetingDescription = $(this).attr("data-description"),
            password = $(this).attr("data-password"),
            meetingIdAuto = $(this).attr("data-auto"),
            meetingDate = $(this).attr("data-date"),
            meetingTime = $(this).attr("data-time"),
            meetingTimezone = $(this).attr("data-timezone");

        meetingId = $(this).data("id");

        $(".active-meeting").removeClass("active-meeting");
        $(this).addClass("active-meeting");

        updateDetail(meetingTitle, meetingDescription, password, meetingIdAuto, meetingDate, meetingTime, meetingTimezone);
    });

    //set meeting details
    function updateDetail(title, description, password, meetingIdAuto, date, time, timezone) {
        title = title.trim();
        $(
            '#meetingTitleDetail, .meeting-card[data-id="' +
            meetingId +
            '"] .meeting-title'
        ).text(title);
        $("#meetingDescriptionDetail").text(description ? description : "-");
        $('.meeting-card[data-id="' + meetingId + '"] .meeting-description').text(
            description ?
                description.length > 40 ?
                    description.substring(0, 40) + "..." :
                    description :
                "-"
        );
        $("#meetingStart").attr("href", "meeting/" + meetingIdAuto);
        $("#invite, #edit, #delete").attr("data-id", meetingId);
        $("#meetingPasswordDetail").text(password ? password : "-");
        $("#meetingIdDetail").text(meetingIdAuto);
        $("#meetingDateDetail").text(date ? date : "-");
        $("#meetingTimeDetail").text(time ? time : "-");
        $("#meetingTimezoneDetail").text(timezone ? timezone : "-");
    }

    //ajax call to create a meeting
    $("#meetingsForm").on("submit", function (e) {
        e.preventDefault();

        $("#save").attr("disabled", true);

        $.ajax({
            url: "create-meeting",
            data: htmlEscapeArray($(this).serializeArray()),
            type: "post",
        })
            .done(function (data) {
                data = JSON.parse(data);
                $("#save").attr("disabled", false);

                if (data.success) {
                    showSuccess(languages.meeting_created);
                    $("#meetingsForm")[0].reset();
                    $("#emptyDetails").attr("hidden", true);
                    $("#meetingDetail").removeAttr("hidden");
                    $("#createMeeting").modal("hide");

                    addMeeting(data.data);
                } else {
                    showError(data.error);
                }
            })
            .catch(function () {
                showError();
                $("#save").attr("disabled", false);
            });
    });

    //add newly created meeting to the div
    function addMeeting(data) {
        meetingId = data.id;

        $("#emptyMeeting").attr("hidden", true);

        if ($(".active-meeting")) {
            $(".active-meeting").removeClass("active-meeting");
        }

        let meeting =
            '<div class="card w-100 mb-2 mt-1 pr-4 meeting-card active-meeting" data-description="' +
            (data.description ? data.description : "") +
            '" data-id="' +
            data.id +
            '" data-auto="' +
            data.meeting_id +
            '" data-password="' +
            (data.password ? data.password : "") +
            '" data-date="' +
            (data.date ? data.date : "") +
            '" data-time="' +
            (data.time ? data.time : "") +
            '" data-timezone="' +
            (data.timezone ? data.timezone : "") +
            '">' +
            '<div class="card-body">' +
            '<h5 class="card-title meeting-title font-weight-bold mb-3">' +
            data.title +
            "</h5>" +
            '<p class="card-text meeting-description">' +
            (data.description ?
                data.description.length > 40 ?
                    data.description.substring(0, 40) + "..." :
                    data.description :
                "-") +
            "</p>" +
            "</div>" +
            "</div>";

        $(".meeting-list").prepend(meeting);
        updateDetail(data.title, data.description, data.password, data.meeting_id, data.date, data.time, data.timezone);

        $(".search-meeting").removeAttr("hidden");
    }

    //generate a random meeting ID for new meetings
    function generateMeetingId() {
        return Math.random().toString(36).substr(2, 9);
    }

    //set meeting ID in the modal
    $("#createMeeting").on("show.bs.modal", function () {
        let meetingId = generateMeetingId();

        $("#meetingId").text(meetingId);
        $("#meetingsFormId").val(meetingId);
    });

    //ajax call to delete a meeting
    $("#delete").on("click", function (e) {
        e.preventDefault();

        if (confirm(languages.confirmation)) {
            $.ajax({
                url: "delete-meeting",
                data: {
                    id: meetingId,
                },
                type: "post",
            })
                .done(function (data) {
                    data = JSON.parse(data);

                    if (data.success) {
                        showSuccess(languages.meeting_deleted);
                        $(".active-meeting").remove();

                        if ($(".meeting-card").length) {
                            $(".meeting-card")[0].click();
                        } else {
                            $("#meetingDetail, .search-meeting").attr("hidden", true);
                            $("#emptyMeeting, #emptyDetails").removeAttr("hidden");
                        }
                    } else {
                        showError();
                    }
                })
                .catch(function () {
                    showError();
                    $("#save").attr("disabled", false);
                });
        }
    });

    //copy meeting URL to the clipboard
    $("#copy").on("click", function (e) {
        e.preventDefault();
        let link =
            location.protocol +
            "//" +
            location.host +
            "/" +
            $("#meetingStart").attr("href");
        var inp = document.createElement("input");
        inp.style.display = "hidden";
        document.body.appendChild(inp);
        inp.value = link;
        inp.select();
        document.execCommand("copy", false);
        inp.remove();
        showSuccess(languages.link_copied);
    });

    //open edit meeting modal and set the details
    $("#edit").on("click", function () {
        let id = meetingId,
            meetingCard = $('.meeting-card[data-id="' + id + '"]'),
            title = meetingCard.find(".meeting-title").text().trim(),
            description = $("#meetingDescriptionDetail").text().trim(),
            password = meetingCard.attr("data-password"),
            meetingIdAuto = meetingCard.attr("data-auto"),
            meetingDate = meetingCard.attr("data-date"),
            meetingTime = meetingCard.attr("data-time"),
            meetingTimezone = meetingCard.attr("data-timezone");

        $("#editMeeting").modal("show");

        $("#titleEdit").val(title);
        $("#descriptionEdit").val(description);
        $("#passwordEdit").val(password);
        $("#meetingIdEdit").text(meetingIdAuto);
        $("#meetingsFormIdEdit").val(meetingId);
        $("#dateEdit").val(formatDate(meetingDate));
        $("#timeEdit").val(formatTime(meetingTime));
        $("#timezoneEdit").val(meetingTimezone);
    });

    //ajax call to save the edited meeting
    $("#meetingsFormEdit").on("submit", function (e) {
        e.preventDefault();

        $("#saveEdit").attr("disabled", true);

        $.ajax({
            url: "edit-meeting",
            data: htmlEscapeArray($(this).serializeArray()),
            type: "post",
        })
            .done(function (data) {
                data = JSON.parse(data);
                $("#saveEdit").attr("disabled", false);

                if (data.success) {
                    showSuccess(languages.meeting_updated);
                    $("#meetingsFormEdit")[0].reset();
                    $("#emptyDetails").attr("hidden", true);
                    $("#meetingDetail").removeAttr("hidden");
                    $("#editMeeting").modal("hide");
                    $(".card").find(`[data-id='${data.data.id}']`).attr('data-description', data.data.description);
                    $(".card").find(`[data-id='${data.data.id}']`).attr('data-password', data.data.password);
                    $(".card").find(`[data-id='${data.data.id}']`).attr('data-date', data.data.date);
                    $(".card").find(`[data-id='${data.data.id}']`).attr('data-time', data.data.time);
                    $(".card").find(`[data-id='${data.data.id}']`).attr('data-timezone', data.data.timezone);

                    updateDetail(
                        data.data.title,
                        data.data.description,
                        data.data.password,
                        data.data.meeting_id,
                        data.data.date,
                        data.data.time,
                        data.data.timezone
                    );
                } else {
                    showError();
                }
            })
            .catch(function () {
                showError();
                $("#saveEdit").attr("disabled", false);
            });
    });

    //ajax call to get the invited people's email
    $("#invite").on("click", function () {
        $(".invite-list").html("");
        $("#showInvites").modal("show");
        $("#inviteId").val(meetingId);

        $.ajax({
            url: "get-invites",
            data: {
                id: meetingId,
            },
        })
            .done(function (data) {
                data = JSON.parse(data);

                if (data.success) {
                    data.data.forEach((email) => {
                        let section =
                            '<li class="list-group-item" data-value="' +
                            email +
                            '">' +
                            email +
                            "</li >";
                        $(".invite-list").prepend(section);
                    });
                } else {
                    showError();
                }
            })
            .catch(function () {
                showError();
            });
    });

    //ajax call to send email invitation
    $("#inviteForm").on("submit", function (e) {
        e.preventDefault();

        const emails = $("#inviteEmail").val();

        $(this)[0].reset();

        showInfo(languages.sending_invite);

        $.ajax({
            url: "send-invite",
            data: {
                'id': inviteId.value,
                'emails': JSON.stringify(emails)
            },
            type: "post",
        })
            .done(function (data) {
                data = JSON.parse(data);

                if (data.success) {
                    $('#inviteEmail').val(null).trigger('change');
                    showSuccess(languages.invite_sent);

                    emails.forEach((email) => {
                        let section =
                            '<li class="list-group-item" data-value="' +
                            email +
                            '">' +
                            email +
                            "</li>";
                        $(".invite-list").prepend(section);
                    });
                } else {
                    showError();
                }
            })
            .catch(function () {
                showError();
            });
    });

    //ajax call to check if the meeting exist or not
    $("#meetingDashboard").on("submit", function (e) {
        e.preventDefault();

        if (conferenceId.value.length !== 9) {
            showError(languages.no_meeting);
            return;
        }

        $("#join").attr("disabled", true);

        $.ajax({
            url: "check-meeting",
            data: htmlEscapeArray($(this).serializeArray()),
            type: "post",
        })
            .done(function (data) {
                data = JSON.parse(data);
                $("#join").attr("disabled", false);

                if (data.success) {
                    location.href = "meeting/" + data.id;
                } else {
                    showError(languages.no_meeting);
                }
            })
            .catch(function () {
                showError(languages.no_meeting);
                $("#join").attr("disabled", false);
            });
    });

    //to prevent XSS vulnerability
    function htmlEscapeArray(input) {
        let data = {};
        $.each(input, function () {
            data[this.name] = this.value
                .replace(/&/g, '')
                .replace(/"/g, '')
                .replace(/'/g, '')
                .replace(/</g, '')
                .replace(/>/g, '');
        });
        return data;
    }

    //format date
    function formatDate(date) {
        return date ? date.split("-").reverse().join("-") : '-';
    }

    //format time
    function formatTime(time12h) {
        if (!time12h) return '-';

        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');

        if (hours === '12') {
            hours = '00';
        }

        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }

        return hours + ':' + minutes;
    }

    //add to google calendar
    $("#addToGoogle").on('click', function () {
        if (meetingDateDetail.innerText == "-" || meetingTimeDetail.innerText == "-") {
            showInfo(languages.calendar_check);
            return;
        }

        let title = meetingTitleDetail.innerText;
        let description = meetingStart.href + "%0A" + languages.password + meetingPasswordDetail.innerText + "%0A" + meetingDescriptionDetail.innerText;
        let dateTime = new Date(convertDate(meetingDateDetail.innerText) + " " + meetingTimeDetail.innerText);
        let startDate = getISOString(dateTime);
        let endDate = getISOString(new Date(dateTime.getTime() + timeLimit * 60000));

        let link = "http://www.google.com/calendar/event?action=TEMPLATE&dates=" + startDate + "Z%2F" + endDate + "Z&details=" + description + "&text=" + title;

        window.open(link);
    });

    //add to outlook calendar
    $("#addToOutlook").on('click', function () {
        if (meetingDateDetail.innerText == "-" || meetingTimeDetail.innerText == "-") {
            showInfo(languages.calendar_check);
            return;
        }

        let title = meetingTitleDetail.innerText;
        let description = meetingStart.href + "<br>" + languages.password + meetingPasswordDetail.innerText + "<br>" + meetingDescriptionDetail.innerText;
        let dateTime = new Date(convertDate(meetingDateDetail.innerText) + " " + meetingTimeDetail.innerText);
        let startDate = getISOStringOutlook(dateTime);
        let endDate = getISOStringOutlook(new Date(dateTime.getTime() + timeLimit * 60000));

        let link = "https://outlook.live.com/calendar/0/deeplink/compose?allday=false&body=" + description + "&enddt=" + endDate + "Z&path=%2Fcalendar%2Faction%2Fcompose&rru=addevent&startdt=" + startDate + "Z&subject=" + title;

        window.open(link);
    });

    //convert date
    function convertDate(date) {
        let x = date.split('-');
        return x[1] + '-' + x[0] + '-' + x[2];
    }

    //get ISO string
    function getISOString(dateTime) {
        return dateTime.toISOString().replace(/-/g, "").replace(/:/g, "").slice(0, 15);
    }

    //get ISO string outlook
    function getISOStringOutlook(dateTime) {
        return dateTime.toISOString().slice(0, 19);
    }

    //redirect to create instant meeting
    $("#instantMeeting").on('click', function () {
        location.href = '/instant/' + generateMeetingId() + "/" + new Date().toLocaleTimeString([], { hour: 'numeric', minute: 'numeric' });
    });

    //search meetings
    $("#search").on('keyup', function () {
        const titles = $(".meeting-card .meeting-title");

        for (const title in Object.keys(titles)) {
            if (titles.hasOwnProperty(title)) {
                if (titles[title].innerText.includes(this.value)) {
                    titles[title].parentElement.parentElement.style.display = 'block';
                } else {
                    titles[title].parentElement.parentElement.style.display = 'none';
                }
            }
        }
    });

    //set select2 for invite dropdown
    $('#inviteEmail').select2({
        allowClear: true,
        tags: true,
    });
})();