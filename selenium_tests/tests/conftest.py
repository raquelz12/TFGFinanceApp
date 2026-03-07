import pytest
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

from pages.login_page import LoginPage
from pages.category_page import CategoryPage


@pytest.fixture
def category_page():

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install())
    )
    driver.maximize_window()

    login_page = LoginPage(driver)
    login_page.open()
    login_page.login("test@test.test", "Test1234")

    category_page = CategoryPage(driver)
    category_page.open()

    yield category_page

    driver.quit()