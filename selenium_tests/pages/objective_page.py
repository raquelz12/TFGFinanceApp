from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

class ObjectivesPage:
    URL = "http://host.docker.internal/TFGFinanceApp/app/objectives.php"

    OPEN_ADD_MODAL = (By.CSS_SELECTOR, "button[data-bs-target='#addObjectiveModal']")
    
    ADD_NAME_INPUT = (By.CSS_SELECTOR, "#addObjectiveModal #name")
    ADD_TARGET_INPUT = (By.CSS_SELECTOR, "#addObjectiveModal #targetAmount")
    ADD_INITIAL_INPUT = (By.CSS_SELECTOR, "#addObjectiveModal #amount")
    ADD_DATE_INPUT = (By.CSS_SELECTOR, "#addObjectiveModal #objective_date")
    ADD_SUBMIT_BTN = (By.ID, "addObjectiveButton")

    EDIT_AMOUNT_INPUT_ACTIVE = (By.CSS_SELECTOR, ".modal.show #amount")
    EDIT_SUBMIT_ACTIVE = (By.CSS_SELECTOR, ".modal.show #saveChangesButton")
    
    ADD_MONEY_INPUT_ACTIVE = (By.CSS_SELECTOR, ".modal.show #amount")
    ADD_MONEY_SUBMIT_ACTIVE = (By.CSS_SELECTOR, ".modal.show #addMoneyButton")

    DELETE_CONFIRM_ACTIVE = (By.CSS_SELECTOR, ".modal.show #deleteObjectiveButton")

    MESSAGE = (By.CLASS_NAME, "message")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)
        self.wait.until(lambda d: "login" not in d.current_url)

    def add_objective(self, name, target, initial, date_str):
        self.wait.until(
            EC.element_to_be_clickable(self.OPEN_ADD_MODAL)
        ).click()
        
        name_input = self.wait.until(
            EC.visibility_of_element_located(self.ADD_NAME_INPUT)
        )
        name_input.clear()
        name_input.send_keys(name)
        
        self.driver.find_element(*self.ADD_TARGET_INPUT).send_keys(target)

        self.driver.find_element(*self.ADD_INITIAL_INPUT).send_keys(initial)
        
        self.driver.find_element(*self.ADD_DATE_INPUT).send_keys(date_str)
        
        submit_btn = self.driver.find_element(*self.ADD_SUBMIT_BTN)
        submit_btn.click()
        
        time.sleep(0.5)
        self.wait.until(EC.presence_of_element_located((By.XPATH, f"//*[contains(text(), '{name}')]")))

    def edit_objective_target(self, objective_name, new_target):
        xpath_btn = f"//div[contains(@class, 'objective-card') and .//h2[text()='{objective_name}']]//button[@title='Editar']"
        edit_button = self.wait.until(
            EC.element_to_be_clickable((By.XPATH, xpath_btn))
        )
        edit_button.click()

        amount_input = self.wait.until(
            EC.visibility_of_element_located(self.EDIT_AMOUNT_INPUT_ACTIVE)
        )
        amount_input.clear()
        amount_input.send_keys(new_target)

        save_btn = self.wait.until(EC.element_to_be_clickable(self.EDIT_SUBMIT_ACTIVE))
        save_btn.click()

        time.sleep(0.5)
        self.wait.until(EC.staleness_of(save_btn))
        
    def add_money_to_objective(self, objective_name, amount_to_add):
        xpath_btn = f"//div[contains(@class, 'objective-card') and .//h2[text()='{objective_name}']]//button[@title='Añadir dinero']"
        add_money_btn = self.wait.until(
            EC.element_to_be_clickable((By.XPATH, xpath_btn))
        )
        add_money_btn.click()

        amount_input = self.wait.until(
            EC.visibility_of_element_located(self.ADD_MONEY_INPUT_ACTIVE)
        )
        amount_input.clear()
        amount_input.send_keys(amount_to_add)

        save_btn = self.wait.until(EC.element_to_be_clickable(self.ADD_MONEY_SUBMIT_ACTIVE))
        save_btn.click()

        time.sleep(0.5)
        self.wait.until(EC.staleness_of(save_btn))

    def delete_objective(self, objective_name):
        xpath_btn = f"//div[contains(@class, 'objective-card') and .//h2[normalize-space(text())='{objective_name}']]//button[@title='Eliminar objetivo']"
        delete_button = self.wait.until(
            EC.element_to_be_clickable((By.XPATH, xpath_btn))
        )
        delete_button.click()
        
        confirm_btn = self.wait.until(
            EC.element_to_be_clickable(self.DELETE_CONFIRM_ACTIVE)
        )
        confirm_btn.click()
        
        time.sleep(0.5)
        self.wait.until(EC.staleness_of(confirm_btn))

    def objective_exists(self, objective_name):
        try:
            xpath = f"//div[contains(@class, 'objective-card') and .//h2[normalize-space(text())='{objective_name}']]"
            WebDriverWait(self.driver, 2).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            return True
        except:
            return False
            
    def get_objective_progress(self, objective_name):
        xpath_card = f"//div[contains(@class, 'objective-card') and .//h2[text()='{objective_name}']]"
        card = self.driver.find_element(By.XPATH, xpath_card)
        
        try:
            amount_text = card.find_element(By.CLASS_NAME, "objective-amount").text
            parts = amount_text.split("/")
            current = parts[0].replace("€", "").strip()
            target = parts[1].replace("€", "").strip()
            return current, target
        except:
            return "0", "0"