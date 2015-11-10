using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using FluentData;

namespace registration.tests
{
    public class BoteventDatabase
    {
        public IDbContext Context =>
            new DbContext().ConnectionString("Server=127.0.0.1;Database=botevent;Uid=root;Pwd=;", new MySqlProvider());

        public void Reset()
        {
            Context.Sql("delete from divisions").Execute();
            Context.Sql("delete from entry").Execute();
            Context.Sql("delete from event").Execute();
            Context.Sql("delete from event_divisions").Execute();
            Context.Sql("delete from event_entries").Execute();
            Context.Sql("delete from event_people").Execute();
            Context.Sql("delete from event_registrations").Execute();
            Context.Sql("delete from person").Execute();
            Context.Sql("delete from team").Execute();
            Context.Sql("delete from team_members").Execute();
        }

        public int AddPerson(string fullName, string dob = "", string email = "", string phonenum = "", string pictureUrl = "", string password = "")
        {
            if (!string.IsNullOrWhiteSpace(password))
            {
                password = CryptSharp.Crypter.Blowfish.Crypt(password);
            }

            return Context.Insert("person")
                .Column("fullname", fullName)
                .Column("email", email)
                .Column("phonenum", phonenum)
                .Column("picture_url", pictureUrl)
                .Column("dob", dob)
                .Column("password", password)
                .ExecuteReturnLastId<int>();
        }

        public int AddTeam(string name, string url = "", string addr1 = "", string addr2 = "",
            string city = "", string state = "", string zip = "",
            string country = "", string description = "")
        {
            return Context.Insert("team")
                .Column("name", name)
                .Column("url", url)
                .Column("addr1", addr1)
                .Column("addr2", addr2)
                .Column("city", city)
                .Column("state", state)
                .Column("zip", zip)
                .Column("country", country)
                .Column("description", description)
                .Column("created", DateTime.Now)
                .ExecuteReturnLastId<int>();
        }

        public void AddTeamMember(int teamId, int personId)
        {
            Context.Insert("team_members")
                .Column("team", teamId)
                .Column("person", personId)
                .Execute();
        }

        public int AddEntry(int teamId, string name, string pictureUri = "",
            string description = "")
        {
            return Context.Insert("entry")
                .Column("team", teamId)
                .Column("name", name)
                .Column("picture_url", pictureUri)
                .Column("description", description)
                .ExecuteReturnLastId<int>();
        }

        public int AddEvent(string name, DateTime? startDate = null,
            DateTime? endDate = null, DateTime? registrationEnds = null,
            string location = "", string uri = "", string description = "", string image = "",
            string smallImage = "", decimal feePerPerson = 0)
        {
            return Context.Insert("event")
                .Column("name", name)
                .Column("image", image)
                .Column("smallimage", smallImage)
                .Column("description", description)
                .Column("startdate", startDate ?? DateTime.UtcNow.Date.AddDays(2))
                .Column("enddate", endDate ?? DateTime.UtcNow.Date.AddDays(3))
                .Column("registrationends", registrationEnds ?? DateTime.UtcNow.Date.AddDays(1))
                .Column("websiteurl", uri)
                .Column("location", location)
                .Column("feeperperson", feePerPerson)
                .ExecuteReturnLastId<int>();
        }

        public int AddDivisionToEvent(int eventId, string name, string description = "",
            string ruleurl = "", int maxEntries = 0, decimal price = 0.0m, int maxEntriesPerTeam = 0)
        {
            var divId = Context.Insert("divisions")
                .Column("name", name)
                .ExecuteReturnLastId<int>();

            return Context.Insert("event_divisions")
                .Column("event", eventId)
                .Column("division", divId)
                .Column("description", description)
                .Column("ruleurl", ruleurl)
                .Column("maxEntries", maxEntries)
                .Column("price", price)
                .Column("max_entries_per_team", maxEntriesPerTeam)
                .ExecuteReturnLastId<int>();
        }

        public void CreateRegistration(int eventId, int teamId, int captainId, int[] people, RegistrationEntry[] entries)
        {
            var registrationId = Context.Insert("event_registrations")
                .Column("event", eventId)
                .Column("team", teamId)
                .Column("status", "new")
                .Column("captain", captainId)
                .Column("due", 0)
                .ExecuteReturnLastId<int>();

            foreach (var person in people)
            {
                Context.Insert("event_people")
                .Column("event_registration", registrationId)
                .Column("person", person)
                .ExecuteReturnLastId<int>();
            }

            foreach (var entry in entries)
            {
                Context.Insert("event_entries")
                .Column("event_registration", registrationId)
                .Column("entry", entry.EntryId)
                .Column("driver", entry.DriverId)
                .Column("event_division", entry.Division)
                .ExecuteReturnLastId<int>();
            }
        }
    }

    public class RegistrationEntry
    {
        public int EntryId { get; set; }
        public int Division { get; set; }
        public int DriverId { get; set; }
    }
}
