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

        public int AddPerson(string fullName, string dob, string email, string phonenum, string pictureUrl, string password)
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
    }
}
