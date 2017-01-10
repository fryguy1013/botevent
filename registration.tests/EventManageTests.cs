using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using FluentAutomation;

using NUnit.Framework;

namespace registration.tests
{
    [TestFixture]
    public class EventManageTests : BaseTest
    {
        private int _eventId;
        private int _ownerId;
        private List<int> _entries = new List<int>();
        private int _teamId;
        private int _divisionId;

        [SetUp]
        public void Setup()
        {
            _db.Reset();
            _ownerId = _db.AddPerson(
                "test name",
                "1/1/1980",
                "testuser@example.com",
                "916-555-1234",
                "sample_badge_photo.jpg",
                "password1");

            //_teamId = _db.AddTeam("FakeFakerson");
            //_db.AddTeamMember(_teamId, _personId);
            //_entries.Add(_db.AddEntry(_teamId, "robot 1"));
            //_entries.Add(_db.AddEntry(_teamId, "robot 2"));
            //_entries.Add(_db.AddEntry(_teamId, "robot 3"));

            _eventId = _db.AddEvent("Some Event", ownerId: _ownerId, feePerPerson: 5m);
            _divisionId = _db.AddDivisionToEvent(_eventId, "division", price: 10m);

            FastRelogin("testuser@example.com", "password1");
        }

        [Test]
        public void WhenEventDoesntExist_ExpectManageShowsUsefulErrorMessage()
        {
            I.Open(BaseUrl + "/event/manage/12345");
            I.Assert
                .Text(t => t.Contains("That event does not exist")).In("div.error");
        }

        [Test]
        public void WhenViewingEventAsOwner_ExpectLinkToManage()
        {
            I.Open(BaseUrl + "/event/view/" + _eventId);
            I.Assert
                .Exists($"a[href='{BaseUrl}/event/manage/{_eventId}']");
        }

        [Test]
        public void WhenManaging_ExpectAmountDueShown()
        {
            CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId);
            I.Assert
                .Text("Status: new | Due: $20").In("div.event_reg_minordetails div");
        }

        [Test]
        public void WhenManaging_ExpectNotesField()
        {
            CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId);
            I.Assert
                .Exists($"textarea[name='notes']");
        }

        [Test]
        public void WhenSettingNotes_ExpectNotesFieldUpdated()
        {
            var entryId = CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Click("div.event_reg_minordetails")
                .Enter("Some notes for the person").WithoutEvents().In("textarea[name='notes']")
                .Click("input[type=submit][value=Save]");

            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Assert
                .Value("Some notes for the person").In("textarea[name='notes']");
        }

        [Test]
        public void WhenSettingPaid_ExpectAmountDueUpdated()
        {
            var entryId = CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Click("div.event_reg_minordetails")
                .Enter("20").WithoutEvents().In("input[name='amount_paid']")
                .Click("input[type=submit][value=Save]");

            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Assert
                .Text("Status: new | Due: $0").In("div.event_reg_minordetails div");
        }

        [Test]
        public void WhenSettingOverpaid_ExpectAmountDueUpdated()
        {
            var entryId = CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Click("div.event_reg_minordetails")
                .Enter("30").WithoutEvents().In("input[name='amount_paid']")
                .Click("input[type=submit][value=Save]");

            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Assert
                .Text("Status: new | Overpaid: $10").In("div.event_reg_minordetails div");
        }

        // todo
        //[Test]
        //public void WhenPaidWithdrawn_ExpectShowsOnManage()
        //{
        //    var entryId = CreateEntry(20);
        //    _db.UpdateEntry(entryId, "withdrawn");

        //    I.Open(BaseUrl + "/event/manage/" + _eventId)
        //        .Assert
        //        .Text("Status: withdrawn | Overpaid: $20").In("div.event_reg_minordetails div");
        //}

        [Test]
        public void WhenUpdatingStatus_ExpectLogShownOfPreviousMessages()
        {
            var entryId = CreateEntry();
            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Click("div.event_reg_minordetails")
                .Select("Pending Payment").From("select[name=status]")
                .Enter("Where's my money!").WithoutEvents().In("textarea[name='message']")
                .Click("input[type=submit][value=Change]");

            I.Open(BaseUrl + "/event/manage/" + _eventId)
                .Click("div.event_reg_minordetails")
                .Assert
                .Text("Status: new | Overpaid: $10").In("div.event_reg_minordetails div");
        }

        private int CreateEntry(decimal paid = 0)
        {
            var teamId = _db.AddTeam("Other fake team");
            var personId = _db.AddPerson("test name 2", email: "testuser2@example.com", password: "password1");
            _db.AddTeamMember(teamId, personId);
            var entry = _db.AddEntry(teamId, "robot 1");

            if (paid != 0)
                _db.UpdateRegistrationPaid(_eventId, teamId, paid, "");

            return _db.CreateRegistration(_eventId, teamId, personId, new[]
            {
                personId
            }, new[]
            {
                new RegistrationEntry
                {
                    Division = _divisionId,
                    DriverId = personId,
                    EntryId = entry
                }
            }, 20);
        }
    }
}
