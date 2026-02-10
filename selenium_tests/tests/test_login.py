from pages.login_page import LoginPage
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_login_correcto(driver):
    login = LoginPage(driver)
    login.open()
    login.login("raquel@gmail.com", "Raquel5477")

    wait = WebDriverWait(driver, 10)
    logout_btn = wait.until(
        EC.visibility_of_element_located((By.ID, "addExpenseButton"))
    )

    assert logout_btn.is_displayed()
