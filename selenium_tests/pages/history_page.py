from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC

class HistoryPage:
    URL = "http://host.docker.internal/TFGFinanceApp/app/history.php"

    ORDER_SELECT = (By.ID, "order")
    HISTORY_CARD = (By.CLASS_NAME, "history-card")
    EMPTY_MSG = (By.XPATH, "//section[@class='history-section']/p[contains(text(), 'No hay datos')]")
    FIRST_DETAIL_BTN = (By.CSS_SELECTOR, ".history-card .button-app")

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def open(self):
        self.driver.get(self.URL)
        self.wait.until(lambda d: "login" not in d.current_url)

    def change_order(self, order_value):
        select_elem = self.wait.until(
            EC.element_to_be_clickable(self.ORDER_SELECT)
        )
        Select(select_elem).select_by_value(order_value)
        self.wait.until(
            EC.staleness_of(select_elem)
        )

    def get_selected_order(self):
        select_elem = self.wait.until(
            EC.presence_of_element_located(self.ORDER_SELECT)
        )
        return Select(select_elem).first_selected_option.get_attribute("value")

    def has_history_data(self):
        try:
            self.driver.find_element(*self.HISTORY_CARD)
            return True
        except:
            return False

    def click_first_detail(self):
        btn = self.wait.until(
            EC.element_to_be_clickable(self.FIRST_DETAIL_BTN)
        )
        btn.click()
        self.wait.until(lambda d: "history_detailed.php" in d.current_url)