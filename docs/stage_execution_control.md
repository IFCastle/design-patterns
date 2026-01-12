# Execution plan with stage control

Let's consider the task of processing a server request, which consists of the following stages:

* raw request processing
* mid-level processing
* dispatching
* action execution
* response handling

Suppose each stage consists of handlers that may not be mandatory. 
For example, the mid-level request processing stage has many handlers 
that are activated depending on the content type. 

If one of the handlers in this stage is executed, 
the other handlers do not need to be triggered.

To implement such logic, it is recommended to use the `ExecutionPlan` pattern along with the `PlanExecutorWithStageControl` 
strategy, which allows handlers to alter the execution flow of the plan.

The class allows Stage handlers to modify the execution
order of the plan by either terminating the current stage processing or directly moving to the next one.

To control the order of stage processing,
the handler must return an object of the `StagePointer` class,
which clearly specifies how the execution order should be modified.

Possible modifications:
- `finishPlan`   - stop the plan execution.
- `goToStage`    - move to the specified stage.
- `breakCurrent` - stop the current stage processing.