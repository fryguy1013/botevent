using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using FluentAutomation;

using NUnit.Framework;
using NUnit.Framework.Interfaces;

namespace registration.tests
{
    [TestFixture]
    public class RegisterTests : BaseTest
    {
        private int _eventId;
        private int _personId;
        private List<int> _entries = new List<int>();
        private int _teamId;

        [SetUp]
        public void Setup()
        {
            _db.Reset();
            _personId = _db.AddPerson(
                "test name",
                "10/13/1982",
                "testuser@example.com",
                "916-555-1234",
                "sample_badge_photo.jpg",
                "password1");

            _teamId = _db.AddTeam("FakeFakerson");
            _db.AddTeamMember(_teamId, _personId);
            _entries.Add(_db.AddEntry(_teamId, "robot 1"));
            _entries.Add(_db.AddEntry(_teamId, "robot 2"));
            _entries.Add(_db.AddEntry(_teamId, "robot 3"));

            _eventId = _db.AddEvent("Some Event");

            Logout();
            Login("testuser@example.com", "password1");
        }

        [Test]
        public void WhenRegistering_ExpectAccepted()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/one-per-team/");

            I.Open(BaseUrl + "/event/register/" + _eventId);
            I.Click("input[name='person[]'][value=" + _personId + "]");
            I.Click("input[name='entry[]'][value=" + _entries[0] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[0] + "]']");
            I.Click("input[name='entry[]'][value=" + _entries[1] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[1] + "]']");
            I.Click("input[type=submit][value=Register]");
            I.Assert
                .Url(u => u.ToString().StartsWith(BaseUrl + "/event_registration/view/"))
                .Text(t => t.Contains("Your registration is pending")).In("div.event_registration_status_description");
        }

        [Test]
        public void WhenRegisteringWithLessThanLimit_ExpectAccepted()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/one-per-team/", maxEntries: 2);

            I.Open(BaseUrl + "/event/register/" + _eventId);
            I.Click("input[name='person[]'][value=" + _personId + "]");
            I.Click("input[name='entry[]'][value=" + _entries[0] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[0] + "]']");
            I.Click("input[type=submit][value=Register]");
            I.Assert
                .Url(u => u.ToString().StartsWith(BaseUrl + "/event_registration/view/"))
                .Text(t => t.Contains("Your registration is pending")).In("div.event_registration_status_description");
        }

        [Test]
        public void WhenRegisteringWithTooManyBotsInClass_ExpectRejected()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/1 lb/", maxEntries: 1);

            I.Open(BaseUrl + "/event/register/" + _eventId);
            I.Click("input[name='person[]'][value=" + _personId + "]");
            I.Click("input[name='entry[]'][value=" + _entries[0] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[0] + "]']");
            I.Click("input[name='entry[]'][value=" + _entries[1] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[1] + "]']");
            I.Click("input[type=submit][value=Register]");
            I.Assert
                .Url(BaseUrl + "/event/register/" + _eventId)
                .Text(t => t.Contains("/1 lb/ is full")).In("div.error");
        }

        [Test]
        public void WhenRegisteringWithTooManyTotalBotsInClass_ExpectRejected()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/1 lb/", maxEntries: 2);

            // create another robots in the division
            var team2Id = _db.AddTeam("Other fake team");
            var person2Id = _db.AddPerson("test name");
            _db.AddTeamMember(team2Id, _personId);
            var entry1 = _db.AddEntry(team2Id, "robot 1");
            _db.CreateRegistration(_eventId, team2Id, person2Id, new[]
            {
                person2Id
            }, new[]
            {
                new RegistrationEntry
                {
                    Division = divisionId,
                    DriverId = person2Id,
                    EntryId = entry1
                }
            }, 0);

            I.Open(BaseUrl + "/event/register/" + _eventId);
            I.Click("input[name='person[]'][value=" + _personId + "]");
            I.Click("input[name='entry[]'][value=" + _entries[0] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[0] + "]']");
            I.Click("input[name='entry[]'][value=" + _entries[1] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[1] + "]']");
            I.Click("input[type=submit][value=Register]");
            I.Assert
                .Url(BaseUrl + "/event/register/" + _eventId)
                .Text(t => t.Contains("/1 lb/ is full")).In("div.error");
        }

        [Test]
        public void WhenRegisteringWithTooManyBotsOnTeamInClass_ExpectRejected()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/one-per-team/", maxEntriesPerTeam: 1);

            I.Open(BaseUrl + "/event/register/" + _eventId);
            I.Click("input[name='person[]'][value=" + _personId + "]");
            I.Click("input[name='entry[]'][value=" + _entries[0] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[0] + "]']");
            I.Click("input[name='entry[]'][value=" + _entries[1] + "]");
            I.Select(Option.Value, divisionId.ToString()).From("select[name='entry_division[" + _entries[1] + "]']");
            I.Click("input[type=submit][value=Register]");
            I.Assert
                .Url(BaseUrl + "/event/register/" + _eventId)
                .Text(t => t.Contains("Entries in /one-per-team/ is limited to 1 per team")).In("div.error");
        }

        [Test]
        public void WhenViewingRegistration_ExpectCanChangeRegistration()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "test");
            var registrationId = _db.CreateRegistration(_eventId, _teamId, _personId, new[]
            {
                _personId
            }, new[]
            {
                new RegistrationEntry
                {
                    Division = divisionId,
                    DriverId = _personId,
                    EntryId = _entries[0]
                }
            }, 0);

            I.Open(BaseUrl + "/event_registration/view/" + registrationId)
                .Assert
                .Attribute("href", $"{BaseUrl}/event/register/{_eventId}/update").On("div.event_registerbutton > a");
        }

        [Test]
        public void WhenRegistrationClosed_ExpectCantChangeRegistration()
        {
            _eventId = _db.AddEvent("Some Event", registrationEnds: DateTime.Now.AddDays(-1));
            var divisionId = _db.AddDivisionToEvent(_eventId, "registration-ended");
            var registrationId = _db.CreateRegistration(_eventId, _teamId, _personId, new[]
            {
                _personId
            }, new[]
            {
                new RegistrationEntry
                {
                    Division = divisionId,
                    DriverId = _personId,
                    EntryId = _entries[0]
                }
            }, 0);

            I.Open(BaseUrl + "/event_registration/view/" + registrationId)
                .Assert
                .Not.Exists("div.event_registerbutton");
        }


        //[Test]
        //public void WhenViewingRegistrationStatus_ExpectHasLinkToPublic()
        //{
        //    var divisionId = _db.AddDivisionToEvent(_eventId, "/one-per-team/", maxEntriesPerTeam: 1);
        //    var registrationId = _db.CreateRegistration(_eventId, _teamId, _personId, new[]
        //    {
        //        _personId
        //    }, new[]
        //    {
        //        new RegistrationEntry
        //        {
        //            Division = divisionId,
        //            DriverId = _personId,
        //            EntryId = _entries[0]
        //        }
        //    });

        //    I.Open(BaseUrl + "/event_registration/view/" + registrationId)
        //        .Assert
        //        .Exists($"a[href='{BaseUrl}/event_regisration/view_public/{registrationId}'");
        //}
    }
}
