import pytest
from selenium.webdriver.support.ui import WebDriverWait
from pages.login_page import LoginPage
from pages.profile_page import ProfilePage

@pytest.fixture
def logged_user(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")
    
    wait = WebDriverWait(driver, 10)
    wait.until(lambda d: "login" not in d.current_url)
    return driver

def test_open_profile_page(logged_user):
    page = ProfilePage(logged_user)
    page.open()
    assert "profile" in logged_user.current_url

def test_email_is_disabled(logged_user):
    page = ProfilePage(logged_user)
    page.open()
    assert page.is_email_disabled()

def test_update_name(logged_user):
    page = ProfilePage(logged_user)
    page.open()
    
    name = page.get_name()
    new_name = "Nombre Test Modificado"
    
    page.update_name(new_name)
    
    page.open()
    assert page.get_name() == new_name
    
    page.update_name(name)
    
    page.open()
    assert page.get_name() == name

def test_password_change(logged_user):
    driver = logged_user
    page = ProfilePage(driver)
    page.open()
    
    try:
        page.attempt_password_change("Test1234", "NewTest1234", "NewTest1234")
        
        page.logout()
        
        login_page = LoginPage(driver)
        login_page.open()
        login_page.login("test@test.test", "NewTest1234")
        
        wait = WebDriverWait(driver, 10)
        wait.until(lambda d: "login" not in d.current_url)
        
        assert "dashboard" in driver.current_url or "profile" in driver.current_url
        
    finally:
        driver.get(ProfilePage.URL)
        page.attempt_password_change("NewTest1234", "Test1234", "Test1234")

def test_password_error(logged_user):
    page = ProfilePage(logged_user)
    page.open()
    
    page.attempt_password_change("Test1234", "Nueva1234", "Distinta1234")
    
    assert "profile" in logged_user.current_url

def test_logout(logged_user):
    page = ProfilePage(logged_user)
    page.open()
    
    page.logout()
    
    assert "login" in logged_user.current_url