from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
import time

class ExpensesPage:
    URL = "http://host.docker.internal/TFGFinanceApp/app/expenses.php"

    OPEN_ADD_MODAL = (By.CSS_SELECTOR, "button[data-bs-target='#ModalAddExpense']")
    
    ADD_NAME_INPUT = (By.CSS_SELECTOR, "#ModalAddExpense #name")
    ADD_AMOUNT_INPUT = (By.CSS_SELECTOR, "#ModalAddExpense #amount")
    ADD_CATEGORY_SELECT = (By.CSS_SELECTOR, "#ModalAddExpense #category_id")
    ADD_SUBMIT_BTN = (By.ID, "saveExpenseButton")

    EDIT_NAME_INPUT_VISIBLE = (By.CSS_SELECTOR, ".modal.show #name")
    EDIT_AMOUNT_INPUT_VISIBLE = (By.CSS_SELECTOR, ".modal.show #amount")
    EDIT_SUBMIT_BTN = (By.CSS_SELECTOR, ".modal.show #saveChangesButton")

    DELETE_CONFIRM_BTN = (By.CSS_SELECTOR, ".modal.show button[type='submit'].btn-danger")

    MESSAGE = (By.CLASS_NAME, "form-error")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)
        self.wait.until(lambda d: "login" not in d.current_url)

    def add_expense(self, name, amount, category_text):
        self.wait.until(EC.element_to_be_clickable(self.OPEN_ADD_MODAL)).click()
        
        name_input = self.wait.until(
            EC.visibility_of_element_located(self.ADD_NAME_INPUT)
        )
        name_input.clear()
        name_input.send_keys(name)
        
        self.driver.find_element(*self.ADD_AMOUNT_INPUT).send_keys(amount)
        
        select_elem = self.driver.find_element(*self.ADD_CATEGORY_SELECT)
        Select(select_elem).select_by_visible_text(category_text)
        
        self.driver.find_element(*self.ADD_SUBMIT_BTN).click()
        
        try:
            self.wait.until(
                EC.staleness_of(name_input)
            )
        except:
            pass
        
        time.sleep(0.5)

    def edit_expense(self, old_name, new_name, new_amount):
        xpath_edit = f"//div[contains(@class, 'transaction-item') and .//span[contains(text(), '{old_name}')]]//button[contains(@class, 'expense-button')][1]"
        
        btn = self.wait.until(
            EC.element_to_be_clickable((By.XPATH, xpath_edit))
        )
        btn.click()

        name_input = self.wait.until(EC.visibility_of_element_located(self.EDIT_NAME_INPUT_VISIBLE))
        name_input.clear()
        name_input.send_keys(new_name)

        amount_input = self.driver.find_element(*self.EDIT_AMOUNT_INPUT_VISIBLE)
        amount_input.clear()
        amount_input.send_keys(new_amount)

        save_btn = self.wait.until(EC.element_to_be_clickable(self.EDIT_SUBMIT_BTN))
        save_btn.click()
        
        time.sleep(0.5)
        self.wait.until(
            EC.invisibility_of_element_located(self.EDIT_SUBMIT_BTN)
        )
        time.sleep(0.5)

    def delete_expense(self, name):
        xpath_delete = f"//div[contains(@class, 'transaction-item') and .//span[contains(text(), '{name}')]]//button[contains(@class, 'expense-button')][2]"
        
        btn = self.wait.until(
            EC.element_to_be_clickable((By.XPATH, xpath_delete))
        )
        btn.click()

        confirm = self.wait.until(
            EC.visibility_of_element_located(self.DELETE_CONFIRM_BTN)
        )
        confirm.click()
        
        time.sleep(0.5)
        
        self.wait.until(
            EC.invisibility_of_element_located(self.DELETE_CONFIRM_BTN)
        )
        time.sleep(0.5)

    def expense_exists(self, name):
        try:
            xpath = f"//div[contains(@class, 'transaction-item')]//span[contains(text(), '{name}')]"
            self.driver.find_element(By.XPATH, xpath)
            return True
        except:
            return False

    def get_expense_amount(self, name):
        xpath_row = f"//div[contains(@class, 'transaction-item') and .//span[contains(text(), '{name}')]]"
        row = self.driver.find_element(By.XPATH, xpath_row)
        
        text = row.find_element(By.XPATH, ".//span[contains(text(), '€')]").text
        
        clean = text.replace("€", "").strip().replace(".", "").replace(",", ".")
        return clean