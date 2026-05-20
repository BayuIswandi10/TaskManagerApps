<template>
  <div class="mobile-root">
    <!-- Header -->
    <header class="mobile-header">
      <div class="header-top">
        <div class="header-brand">
          <span class="brand-logo font-mono">TF</span>
          <span class="brand-name font-mono">TASKFLOW</span>
        </div>
        <button class="logout-icon" @click="auth.logout()" title="Logout">⏻</button>
      </div>

      <div class="header-meta">
        <span class="font-mono user-greeting">USER: {{ auth.user?.name?.toUpperCase() }}</span>
        <span class="font-mono task-count-pill">{{ pendingTasks }} PENDING</span>
      </div>
    </header>

    <!-- Today Alert -->
    <div v-if="todayTasks.length > 0" class="alert-today">
      <span>⚠</span>
      <span>{{ todayTasks.length }} task(s) due TODAY</span>
    </div>

    <!-- Sort Bar -->
    <div class="sort-bar">
      <span class="font-mono sort-label">SORT BY</span>
      <div class="sort-pills">
        <button
          v-for="opt in sortOptions"
          :key="opt.val"
          class="sort-pill font-mono"
          :class="{ active: sortKey === opt.val }"
          @click="changeSort(opt.val)"
        >{{ opt.label }}</button>
      </div>
    </div>

    <!-- Task List -->
    <main class="mobile-main">
      <div v-if="taskStore.loading" class="loading-state font-mono">LOADING...</div>
      <div v-else-if="tasks.length === 0" class="empty-state">
        <div class="font-mono empty-text">[ NO TASKS ]</div>
        <div class="empty-sub">Tap the button below to add one</div>
      </div>
      <div v-else class="task-list">
        <TaskCard
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          :is-today="isToday(task.due_date)"
          @complete="taskStore.markComplete"
        />
      </div>
    </main>

    <!-- FAB -->
    <button class="fab font-mono" @click="showModal = true">+ NEW</button>

    <!-- Modal: full screen on mobile -->
    <div v-if="showModal" class="mobile-modal-backdrop">
      <div class="mobile-modal">
        <div class="modal-top-bar">
          <span class="font-mono modal-label">NEW TASK</span>
          <button @click="showModal = false" class="close-btn font-mono">✕</button>
        </div>
        <hr class="divider" />
        <form @submit.prevent="handleAdd" class="mobile-form">
          <div class="field">
            <label>Task Title</label>
            <input type="text" v-model="form.title" placeholder="Enter task title..." required />
          </div>
          <div class="field">
            <label>Description</label>
            <textarea v-model="form.description" rows="3" placeholder="Optional details..."></textarea>
          </div>
          <div class="field">
            <label>Due Date</label>
            <input type="date" v-model="form.due_date" required />
          </div>
          <div class="field">
            <label>Priority</label>
            <div class="priority-pills">
              <button
                v-for="p in ['Low','Medium','High']"
                :key="p"
                type="button"
                class="p-pill font-mono"
                :class="['p-' + p.toLowerCase(), { active: form.priority === p }]"
                @click="form.priority = p"
              >{{ p }}</button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary submit-btn">SAVE TASK →</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTaskStore } from '@/stores/task'
import TaskCard from '@/components/TaskCard.vue'

const auth = useAuthStore()
const taskStore = useTaskStore()

const showModal = ref(false)
const sortKey = ref('date')
const form = ref({ title: '', description: '', due_date: '', priority: 'Medium' })

const sortOptions = [
  { val: 'date', label: 'DATE' },
  { val: 'priority', label: 'PRIORITY' },
  { val: 'description', label: 'DESC' },
]

const tasks = computed(() => taskStore.tasks)
const todayTasks = computed(() => taskStore.tasksToday())
const pendingTasks = computed(() => tasks.value.filter(t => !t.is_completed).length)

function isToday(d) { return d === new Date().toISOString().slice(0, 10) }

function changeSort(val) {
  sortKey.value = val
  taskStore.setSort(val)
}

async function handleAdd() {
  await taskStore.addTask({ ...form.value })
  form.value = { title: '', description: '', due_date: '', priority: 'Medium' }
  showModal.value = false
}

onMounted(() => taskStore.fetchTasks())
</script>

<style scoped>
.mobile-root {
  display: flex;
  flex-direction: column;
  height: 100vh;
  background: var(--color-bg);
  overflow: hidden;
  position: relative;
}

/* Header */
.mobile-header {
  background: var(--color-surface);
  border-bottom: 2px solid var(--color-border);
  padding: 16px;
  flex-shrink: 0;
}

.header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.header-brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-logo {
  width: 32px;
  height: 32px;
  background: var(--color-accent);
  color: #000;
  font-size: 13px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.brand-name {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: 0.12em;
}

.logout-icon {
  font-size: 20px;
  color: var(--color-text-muted);
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
}

.header-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.user-greeting {
  font-size: 11px;
  color: var(--color-text-muted);
  letter-spacing: 0.06em;
}

.task-count-pill {
  font-size: 11px;
  font-weight: 700;
  background: var(--color-accent);
  color: #000;
  padding: 2px 8px;
  letter-spacing: 0.06em;
}

/* Sort bar */
.sort-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 16px;
  background: var(--color-surface);
  border-bottom: 1px solid var(--color-border);
  flex-shrink: 0;
}

.sort-label {
  font-size: 10px;
  letter-spacing: 0.1em;
  color: var(--color-text-dim);
  white-space: nowrap;
}

.sort-pills {
  display: flex;
  gap: 6px;
}

.sort-pill {
  padding: 3px 10px;
  font-size: 11px;
  letter-spacing: 0.06em;
  background: var(--color-surface-2);
  border: 1px solid var(--color-border);
  color: var(--color-text-muted);
  cursor: pointer;
  transition: all 0.15s;
}
.sort-pill.active {
  background: var(--color-accent-dim);
  border-color: var(--color-accent);
  color: var(--color-accent);
}

/* Main */
.mobile-main {
  flex: 1;
  overflow-y: auto;
  padding: 12px 16px 80px;
}

.task-list {
  display: flex;
  flex-direction: column;
  gap: 1px;
  border: 1px solid var(--color-border);
  background: var(--color-border);
}
.task-list > * { background: var(--color-surface); }

.loading-state, .empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 200px;
  gap: 10px;
  color: var(--color-text-muted);
}

.empty-text { font-size: 16px; letter-spacing: 0.1em; }
.empty-sub { font-size: 12px; color: var(--color-text-dim); }

/* FAB */
.fab {
  position: fixed;
  bottom: 24px;
  right: 20px;
  background: var(--color-accent);
  color: #000;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.06em;
  padding: 14px 22px;
  border: none;
  cursor: pointer;
  z-index: 50;
  box-shadow: 4px 4px 0 rgba(0,0,0,0.4);
}

/* Mobile Add Modal */
.mobile-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.8);
  z-index: 100;
  display: flex;
  align-items: flex-end;
}

.mobile-modal {
  width: 100%;
  background: var(--color-surface);
  border-top: 3px solid var(--color-accent);
  max-height: 90vh;
  overflow-y: auto;
}

.modal-top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
}

.modal-label {
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.12em;
  color: var(--color-accent);
}

.close-btn {
  font-size: 16px;
  color: var(--color-text-muted);
  background: none;
  border: 1px solid var(--color-border);
  padding: 4px 10px;
  cursor: pointer;
}

.mobile-form {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.priority-pills {
  display: grid;
  grid-template-columns: repeat(3,1fr);
  gap: 8px;
  margin-top: 6px;
}

.p-pill {
  padding: 8px;
  text-align: center;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.08em;
  border: 1px solid var(--color-border);
  background: var(--color-surface-2);
  color: var(--color-text-muted);
  cursor: pointer;
  transition: all 0.15s;
}

.p-pill.p-low.active    { border-color: var(--color-success); background: var(--color-success-dim); color: var(--color-success); }
.p-pill.p-medium.active { border-color: var(--color-warn);    background: var(--color-warn-dim);    color: var(--color-warn); }
.p-pill.p-high.active   { border-color: var(--color-danger);  background: var(--color-danger-dim);  color: var(--color-danger); }

.submit-btn { width: 100%; justify-content: center; padding: 14px; font-size: 14px; }

.font-mono { font-family: var(--font-mono); }
</style>
