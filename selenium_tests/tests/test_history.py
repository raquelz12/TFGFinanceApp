import pytest
from selenium.webdriver.support.ui import WebDriverWait
from pages.login_page import LoginPage
from pages.history_page import HistoryPage
from pages.expense_page import ExpensesPage
from selenium.webdriver.common.by import By

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
    assert "history" in logged_user.current_url, f"No se ha redirigido a la página de historial"

def test_change_order(logged_user):
    page = HistoryPage(logged_user)
    page.open()
    assert page.get_selected_order() == "desc", f"El orden por defecto debería ser 'desc'"
    
    page.change_order("asc")
    assert page.get_selected_order() == "asc", f"El orden debería cambiar a 'asc'"

def test_view_detail(logged_user):

    expenses = ExpensesPage(logged_user)
    expenses.open()
    if not expenses.expense_exists("Gasto Historial"):
        expenses.add_expense("Gasto Historial", "10.00", "Alimentación")

    page = HistoryPage(logged_user)
    page.open()
    
    if page.has_history_data():
        page.click_first_detail()
        assert "history_detailed" in logged_user.current_url, f"No se ha redirigido a la página de detalle del historial"

def test_dead_buttons(logged_user):
    page = HistoryPage(logged_user)
    page.open()
    
    driver = logged_user

    all_buttons = driver.find_elements(By.TAG_NAME, "button")
    dead_buttons = []

    for btn in all_buttons:
        btn_type = btn.get_attribute("type")
        btn_toggle = btn.get_attribute("data-bs-toggle")
        btn_dismiss = btn.get_attribute("data-bs-dismiss")
        btn_onclick = btn.get_attribute("onclick")
        btn_text = btn.text.strip() or btn.get_attribute("id") or "Botón sin texto"

        if btn_type != "submit" and not btn_toggle and not btn_dismiss and not btn_onclick:
            dead_buttons.append(btn_text)

    assert len(dead_buttons) == 0, f"Se han detectado botones sin funcionalidad"