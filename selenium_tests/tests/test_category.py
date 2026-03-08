import pytest
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from pages.category_page import CategoryPage
from pages.login_page import LoginPage

@pytest.fixture
def logged_user(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")

    wait = WebDriverWait(driver, 10)
    wait.until(lambda d: "login" not in d.current_url)

    return driver

def category_exists(page, category_name="Alimentación"):
    page.open()
    if not page.category_exists(category_name):
        page.add_category(category_text=category_name, amount="100")

def test_open_category_page(logged_user):
    driver = logged_user
    page = CategoryPage(driver)
    
    page.open()

    wait = WebDriverWait(driver, 10)
    assert wait.until(
        EC.visibility_of_element_located(page.OPEN_ADD_MODAL)
    ).is_displayed()

def test_add_category(logged_user):
    driver = logged_user
    page = CategoryPage(driver)
    page.open()

    if page.category_exists("Alimentación"):
        page.delete_category("Alimentación")

    page.add_category(category_text="Alimentación", amount="100")

    assert page.category_exists("Alimentación")

def test_edit_category(logged_user):
    driver = logged_user
    page = CategoryPage(driver)
    page.open()

    category_name = "Alimentación"
    category_exists(page, category_name)

    page.edit_category(category_name, new_amount="200")

    new_amount = page.get_category_amount(category_name)
    assert new_amount == "200.00"

def test_delete_category(logged_user):
    driver = logged_user
    page = CategoryPage(driver)
    page.open()

    category_name = "Alimentación"
    category_exists(page, category_name)

    page.delete_category(category_name)

    assert not page.category_exists(category_name)

def test_category_over_budget(logged_user):
    driver = logged_user
    page = CategoryPage(driver)
    page.open()

    category_name = "Alimentación"
    category_exists(page, category_name)

    is_over_budget = page.is_category_over_budget(category_name)
    
    assert isinstance(is_over_budget, bool)