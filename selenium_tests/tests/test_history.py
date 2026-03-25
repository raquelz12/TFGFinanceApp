import pytest
from selenium.webdriver.support.ui import WebDriverWait
from pages.login_page import LoginPage
from pages.history_page import HistoryPage
from pages.expense_page import ExpensesPage

@pytest.fixture
def logged_user(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")
    
    wait = WebDriverWait(driver, 10)
    wait.until(lambda d: "login" not in d.current_url)
    return driver

def test_open_history(logged_user):
    page = HistoryPage(logged_user)
    page.open()
    assert "history" in logged_user.current_url

def test_change_order(logged_user):
    page = HistoryPage(logged_user)
    page.open()
    assert page.get_selected_order() == "desc"
    
    page.change_order("asc")
    assert page.get_selected_order() == "asc"

def test_view_detail(logged_user):

    expenses = ExpensesPage(logged_user)
    expenses.open()
    if not expenses.expense_exists("Gasto Historial"):
        expenses.add_expense("Gasto Historial", "10.00", "Alimentación")

    page = HistoryPage(logged_user)
    page.open()
    
    if page.has_history_data():
        page.click_first_detail()
        assert "history_detailed" in logged_user.current_url