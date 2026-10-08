import DashboardController from './DashboardController'
import SearchController from './SearchController'
import RepositoryController from './RepositoryController'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    SearchController: Object.assign(SearchController, SearchController),
    RepositoryController: Object.assign(RepositoryController, RepositoryController),
}

export default Controllers