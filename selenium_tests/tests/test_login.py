from pages.login_page import LoginPage
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_login_correcto(driver):
    login = LoginPage(driver)
    login.open()
    login.login("test@test.test", "Test1234")

    wait = WebDriverWait(driver, 10)
    logout_btn = wait.until(
        EC.visibility_of_element_located((By.ID, "addExpenseButton"))
    )

    assert logout_btn.is_displayed()


def test_login_incorrecto(driver):
    login = LoginPage(driver)
    login.open()
    login.login("usuario@gmail.com", "contraseñaIncorrecta")

    wait = WebDriverWait(driver, 10)
    error_message = wait.until(
        EC.visibility_of_element_located((By.CLASS_NAME, "form_error"))
    )

    assert error_message.is_displayed()

def test_acceso_sin_login(driver):
    driver.get("http://localhost/TFGFinanceApp/app/app_dashboard.php")

    assert "login" in driver.current_url.lower()