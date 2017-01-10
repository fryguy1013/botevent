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
    public class EventTests : BaseTest
    {
        private int _eventId;
        private int _personId;

        [SetUp]
        public void Setup()
        {
            _db.Reset();
            _eventId = _db.AddEvent("Some Event");

            _personId = _db.AddPerson(
                "test name",
                "10/13/1982",
                "testuser@example.com",
                "916-555-1234",
                "sample_badge_photo.jpg",
                "password1");

            Logout();
            Login("testuser@example.com", "password1");
        }

        [Test]
        public void WhenEventDoesntExist_ExpectShowsUsefulErrorMessage()
        {
            I.Open(BaseUrl + "/event/view/12345");
            I.Assert
                .Text(t => t.Contains("That event does not exist")).In("div.error");
        }

        [Test]
        public void WhenEventDoesntExist_ExpectEntriesShowsUsefulErrorMessage()
        {
            I.Open(BaseUrl + "/event/entries/12345/232321313");
            I.Assert
                .Text(t => t.Contains("That event does not exist")).In("div.error");
        }

        [Test]
        public void WhenDivisionDoesntExist_ExpectEntriesShowsUsefulErrorMessage()
        {
            I.Open(BaseUrl + $"/event/entries/{_eventId}/232321313");
            I.Assert
                .Text(t => t.Contains("That division does not exist for this event")).In("div.error");
        }

        [Test]
        public void WhenDivisionDoesntExist_ExpectShowsUsefulErrorMessage()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "/one-per-team/", "this is the description of the event");

            I.Open(BaseUrl + "/event/entries/" + _eventId + "/232321313");
            I.Assert
                .Text(t => t.Contains("That division does not exist for this event")).In("div.error");
        }

        [Test]
        public void WhenDivisionHasDescription_ExpectDescriptionShown()
        {
            var divisionId = _db.AddDivisionToEvent(_eventId, "event with description", "this is the description of the division");

            I.Open(BaseUrl + "/event/entries/" + _eventId + "/" + divisionId);
            I.Assert
                .Text(t => t.Contains("this is the description of the division")).In("div.event_description");
        }

    }
}
