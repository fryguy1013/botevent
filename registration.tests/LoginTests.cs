using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

using FluentAutomation;
using FluentAutomation.Interfaces;

using MySql.Data.MySqlClient;

using NUnit.Framework;

using OpenQA.Selenium.PhantomJS;

namespace registration.tests
{
    [TestFixture]
    public class LoginTests : BaseTest
    {
        protected const string BadgePhoto = @"photo.jpg";

        public LoginTests()
        {
        }

        [SetUp]
        public void Setup()
        {
            //I.Open(BaseUrl + "/install/reset");
            I.Open(BaseUrl + "/login/logout");

            _db.Context.Sql("delete from person").Execute();
            _db.AddPerson(
                "test name",
                "10/13/1982",
                "testuser@example.com",
                "916-555-1234",
                "sample_badge_photo.jpg",
                "password1");
        }

        [Test]
        public void WhenPasswordWrong_ExpectNotLoggedIn()
        {
            Login("testuser@example.com", "WRONG_PASSWORD")
                .Click("input[type=submit]")
                .Assert
                .Url(BaseUrl + "/login")
                .Text(t => t.Contains("Login")).In("#mainheadingright")
                .Text(t => t.Contains("That password is incorrect.")).In("div.error");
        }

        [Test]
        public void WhenPasswordCorrect_ExpectLoggedIn()
        {
            Login("testuser@example.com", "password1")
                .Assert
                .Url(BaseUrl + "/event/all")
                .Text(t => t.Contains("test name")).In("#mainheadingright");
        }

        [Test]
        public void WhenRedirectedFromOtherPlace_ExpectRedirectedWhenLoggedIn()
        {
            I.Open(BaseUrl + "/event/view/59")
                .Enter("testuser@example.com").WithoutEvents().In("input[name=email_addr]")
                .Enter("password1").WithoutEvents().In("input[name=password]")
                .Click("input[type=submit]")
                .Assert
                .Url(BaseUrl + "/event/view/59");
        }

        [Test]
        public void WhenLoggingOut_ExpectRedirectedToLogin()
        {
            Login("testuser@example.com", "password1")
                .Click($"#mainheadingright > a[href='{BaseUrl + "/login/logout"}']")
                .Assert
                .Text(t => t.Contains("Login")).In("#mainheadingright")
                .Url(BaseUrl + "/login");
        }
    }
}
