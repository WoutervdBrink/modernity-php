import DashboardController from './DashboardController'
import PlaygroundController from './PlaygroundController'
import FailedJobsController from './FailedJobsController'
import SearchController from './SearchController'
import RepositoryController from './RepositoryController'
import SnapshotController from './SnapshotController'
import SnapshotFileController from './SnapshotFileController'
import SourceFileController from './SourceFileController'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    PlaygroundController: Object.assign(PlaygroundController, PlaygroundController),
    FailedJobsController: Object.assign(FailedJobsController, FailedJobsController),
    SearchController: Object.assign(SearchController, SearchController),
    RepositoryController: Object.assign(RepositoryController, RepositoryController),
    SnapshotController: Object.assign(SnapshotController, SnapshotController),
    SnapshotFileController: Object.assign(SnapshotFileController, SnapshotFileController),
    SourceFileController: Object.assign(SourceFileController, SourceFileController),
}

export default Controllers