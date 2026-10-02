import DashboardController from './DashboardController'
import SearchController from './SearchController'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    SearchController: Object.assign(SearchController, SearchController),
}

export default Controllers