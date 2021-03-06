using FluentAutomation;
using FluentAutomation.Interfaces;

namespace registration.tests
{
    public class BaseTest : FluentTest
    {
        protected BoteventDatabase _db;
        protected const string BaseUrl = "http://localhost/registration";

        public BaseTest()
        {
            _db = new BoteventDatabase();

            SeleniumWebDriver.Bootstrap(
                SeleniumWebDriver.Browser.Firefox
                );
        }

        protected IActionSyntaxProvider Login(string email, string password)
        {
            return I.Open(BaseUrl + "/login")
                .Enter(email).WithoutEvents().In("input[name=email_addr]")
                .Enter(password).WithoutEvents().In("input[name=password]")
                .Click("input[type=submit]");
        }

        protected IActionSyntaxProvider Logout()
        {
            return I.Open(BaseUrl + "/login/logout");
        }

        protected IActionSyntaxProvider FastRelogin(string email, string password)
        {
            return I.Open(BaseUrl + "/login/logout")
                .Enter(email).WithoutEvents().In("input[name=email_addr]")
                .Enter(password).WithoutEvents().In("input[name=password]")
                .Click("input[type=submit]");

        }
    }

    public static class FluentExtensions
    {
        public static IActionSyntaxProvider UploadRelative(this IActionSyntaxProvider self, string selector, string path)
        {
            var fullPath = System.IO.Path.GetFullPath(path) + "\n";
            return self.Upload(selector, fullPath);
        }
    }
}